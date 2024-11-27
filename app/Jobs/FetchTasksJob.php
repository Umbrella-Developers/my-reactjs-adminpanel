<?php

namespace App\Jobs;

use App\Models\AsanaDonation;
use App\Models\Configuration;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class FetchTasksJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $projectId;
    public $type; // Add type property
    public $userId;
    public $tech_donor_id;

    public function __construct($projectId, $type = null, $userId, $tech_donor_id) // Include type in the constructor
    {
        $this->projectId = $projectId;
        $this->type = $type; // Initialize type
        $this->userId = $userId;
        $this->tech_donor_id = $tech_donor_id;
    }




    public function handle()
{
    $fieldArray = [];
    $configurationsData = Configuration::where('type', 'encryption')->where('value', '1')->get();
    foreach ($configurationsData as $data) {
        $fieldArray[] = $data->name;
    }

    // Get the Asana client
    $client = new Client([
        'base_uri' => 'https://app.asana.com/api/1.0/',
        'headers' => [
            'Authorization' => 'Bearer ' . env('ASANA_ACCESS_TOKEN'),
            'Content-Type' => 'application/json',
        ]
    ]);

    $mainData = [];
    $hasMorePages = true;
    $paginationLimit = 50; // Limit of items per page
    $lastCreatedAt = null; // Track the last task's creation time
    $dueAfterDate = '2024-10-11'; // Filter tasks due after this date
    do {
        // Set up query parameters for filtering and pagination
        $options = [
            'query' => [
                'limit' => $paginationLimit, // Limit results per page
                'custom_fields.1199173512699290.value' => $this->tech_donor_id, // tech_donor_customer_id
                'custom_fields.1206123470176889.value' => 1206123477157607, // Donation Finalization Stage
                'projects.any' => getSetting('project_gid'),
                'opt_fields' => 'created_at,name,resource_type,resource_subtype', // Specify fields to include in the response
                'sort_by' => 'created_at', // Sort by creation time for manual pagination
                // 'due_after' => $dueAfterDate, // Filter tasks due after this date
            ],
        ];

        // If we have the last task's creation time, add a filter to fetch tasks created after it
        if ($lastCreatedAt) {
            $formattedDate =  Carbon::parse($lastCreatedAt)->toIso8601String();
            $options['query']['created_at.before'] = $formattedDate;
        }

        
        // Make the request to Asana's API
        $response = $client->get("workspaces/11605158433449/tasks/search", $options);
        $data = json_decode($response->getBody()->getContents(), true);
        
        // Log::error('Project Options: ' . serialize($options));
        // $last_item = end($data['data']);
        // if(isset($data['data'][0])) {
        //     Log::error('Task Response: ' . serialize($data['data'][0]));
        // }
        

        // Merge the current page data with the mainData array
        $mainData = array_merge($mainData, $data['data']);

        // If we got fewer than $paginationLimit items, we've reached the last page
        if (count($data['data']) < $paginationLimit) {
            $hasMorePages = false;
        } else {
            // Update the lastCreatedAt with the creation time of the last task
            $lastTask = end($data['data']);
            if ($lastTask) {
                $lastCreatedAt = $lastTask['created_at'];
            } else {
                $hasMorePages = false; // No more data
            }
        }

    } while ($hasMorePages);

    // Now, $mainData contains all the tasks


    // Now, $mainData contains all the tasks

    

    // Process the filtered tasks with ProcessTaskJob
    foreach ($mainData as $taskData) {
        ProcessTaskJob::dispatch($taskData['gid'], $this->projectId, $fieldArray, $this->type, $this->userId, $this->tech_donor_id); // Pass the necessary data to ProcessTaskJob
    }
}

    private function getAsanaClient()
    {
        // Implement your method to return the Asana client
    }
}
