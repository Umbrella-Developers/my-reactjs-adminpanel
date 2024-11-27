<?php

namespace App\Jobs;

use App\Models\AsanaDonation;
use App\Models\AsanaDonationField;
use App\Models\AsanaProject;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;

class ProcessTaskJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $taskId;
    private $projectId;
    private $fieldArray = [];
    private $userId;
    private $type; 
    private $tech_donor_id; 

    public function __construct($taskId, $projectId, $fieldArray, $type = null, $userId, $tech_donor_id)
    {
        $this->taskId = $taskId;
        $this->projectId = $projectId;
        $this->fieldArray = $fieldArray;
        $this->type = $type;
        $this->userId = $userId;
        $this->tech_donor_id = $tech_donor_id;
    }

    public function handle()
    {
        $client = new Client([
            'base_uri' => 'https://app.asana.com/api/1.0/',
            'headers' => [
                'Authorization' => 'Bearer ' . env('ASANA_ACCESS_TOKEN'),
                'Content-Type' => 'application/json'
            ]
        ]);

        $task = $this->getTaskDetails($this->taskId, $client);

        // Fetch the project ID
        $getAsanaProjectID = AsanaProject::where('gid', $this->projectId)->first();
        $techDonorCustomerId = null; // To store tech_donor_customer_id from custom fields
        //Log::error('Task Data: ' . serialize($task['data']['custom_fields']) );
        // Fetch user details from custom fields
        foreach ($task['data']['custom_fields'] as $value) {
            $displayValue = $value['display_value'] ?? 0;

            if ($value['gid'] == '1199173512699290') {
                $techDonorCustomerId = $displayValue; // Store the tech_donor_customer_id
            }
        }

        // Verify tech_donor_customer_id against the users table
        $user = User::where('id', $this->userId)->where('tech_donor_customer_id', $techDonorCustomerId)->first();

        // If user doesn't exist, skip the process
        if (!$user) {
            return;
        }

        // Handle different types
        if ($this->type === 'donorDonationSync') {
            $this->handleDonorDonationSync($task, $getAsanaProjectID, $user);
        } elseif ($this->type === 'donorDonationUpdate') {
            $this->handleDonorDonationUpdate($task, $getAsanaProjectID, $user);
        }
    }

    private function handleDonorDonationSync($task, $asanaProjectID, $user)
    {
        $existingDonations = AsanaDonation::where('gid', $task['data']['gid'])->first();
        $donationData = [
            'gid' => $task['data']['gid'],
            'title' => $task['data']['name'],
            'hit_job_id' => 0, // Update this accordingly if needed
            'asana_project_id' => $asanaProjectID->id,
            'status' => 1,
            'user_id' => (int)$user->id, // Store the user ID
        ];

        // Store donation only if it doesn't already exist
        if (!$existingDonations) {
            $newDonation = AsanaDonation::create($donationData);
            $this->storeCustomFields($task, $newDonation['id']);
        }
    }

    private function handleDonorDonationUpdate($task, $asanaProjectID, $user)
    {
        $existingDonations = AsanaDonation::where('gid', $task['data']['gid'])->first();
        // Proceed if the donation exists
        if ($existingDonations) {
            // Update title if changed
            if ($existingDonations->title !== $task['data']['name']) {
                $existingDonations->title = $task['data']['name'];
            }

            // Fetch and update custom fields if they match
            $this->updateCustomFields($task, $existingDonations, $user->tech_donor_customer_id);
            $existingDonations->save();
        }
    }

    private function storeCustomFields($task, $donationID)
    {
        $donationFieldsToInsert = [];
        foreach ($task['data']['custom_fields'] as $value) {
            $displayValue = $value['display_value'] ?? 0;

            $donationFieldsToInsert[] = [
                'gid' => $value['gid'],
                'name' => $value['name'],
                'value' => Crypt::encryptString($displayValue), // Encrypt if needed
                'field_object' => Crypt::encryptString(json_encode([])), // Adjust based on field type
                'field_type' => $value['type'],
                'asana_donation_id' => $donationID,
                'status' => '1',
            ];
        }

        // Insert donation fields
        AsanaDonationField::insert($donationFieldsToInsert);
    }

    private function updateCustomFields($task, $asanaDonation, $techDonorCustomerId)
    {
        foreach ($task['data']['custom_fields'] as $value) {
            $displayValue = $value['display_value'] ?? 0;

            // Only update fields where GID matches and the value is tech_donor_customer_id
            // if ($value['gid'] == '1199173512699290' && $displayValue == $techDonorCustomerId) {
                // Check if the existing field needs to be updated
                $existingField = AsanaDonationField::where('asana_donation_id', $asanaDonation->id)
                    ->where('gid', $value['gid'])
                    ->first();

                if ($existingField) {
                    $existingField->value = Crypt::encryptString($displayValue); // Update encrypted value
                    $existingField->save();
                } else {
                    // If the field doesn't exist, create a new one
                    AsanaDonationField::create([
                        'gid' => $value['gid'],
                        'name' => $value['name'],
                        'value' => Crypt::encryptString($displayValue), // Encrypt if needed
                        'field_object' => Crypt::encryptString(json_encode([])), // Adjust based on field type
                        'field_type' => $value['type'],
                        'asana_donation_id' => $asanaDonation->id,
                        'status' => '1',
                    ]);
                }
            // }
        }
    }


    private function getTaskDetails($taskId, $client)
    {
        $response = $client->get("tasks/{$taskId}");
        return json_decode($response->getBody()->getContents(), true);
    }
}
