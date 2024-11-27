<?php

namespace App\Jobs;

use App\Models\AsanaProject;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use GuzzleHttp\Client;
use App\Jobs\FetchTasksJob;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class FetchProjectsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $workspaceId;
    private $sandboxProjectId;
    public $client;
    public $type;
    public $userId;

    public function __construct($workspaceId, $sandboxProjectId, $type = null, $userId)
    {
        $this->workspaceId = $workspaceId;
        $this->sandboxProjectId = $sandboxProjectId;
        $this->type = $type;
        $this->userId = $userId;
    }

    public function handle()
    {
        // Initialize Guzzle client for Asana API
        $this->client = new Client([
            'base_uri' => 'https://app.asana.com/api/1.0/',
            'headers' => [
                'Authorization' => 'Bearer ' . env('ASANA_ACCESS_TOKEN'),
                'Content-Type' => 'application/json'
            ]
        ]);

        try {
            // Fetch the specific project using sandboxProjectId
            $response = $this->client->get("projects/{$this->sandboxProjectId}");
            $projectData = json_decode($response->getBody()->getContents(), true);

            
            // Check if project data is retrieved successfully
            if (!isset($projectData['data'])) {
                Log::error('Project data not found for GID: ' . $this->sandboxProjectId);
                return; // Exit if project data is not found
            }

            $project = $projectData['data'];
            // Insert or update the project in the database
            $existingProject = AsanaProject::where('gid', $project['gid'])->first();
            

            if (!$existingProject) {
                // Insert new project if it doesn't exist
                AsanaProject::create([
                    'gid' => $project['gid'],
                    'name' => $project['name'],
                    'status' => 1,
                    'workspace_gid' => $this->workspaceId,
                ]);
            } else {
                // Update the project if it already exists
                if ($existingProject->name !== $project['name']) {
                    $existingProject->name = $project['name'];
                    $existingProject->save();
                }
            }
            //Log::error('Project Responsed: ' . serialize($projectData['data']) . ' for GID: ' . $this->sandboxProjectId);
            // Dispatch FetchTasksJob for this project with the correct type
            $user = User::where('id', $this->userId)->first();
            $tech_donor_id = $user->tech_donor_customer_id;
            FetchTasksJob::dispatch($project['gid'], $this->type, $this->userId, $tech_donor_id);

        } catch (\Exception $e) {
            Log::error('Error fetching projectssss: ' . $e->getMessage());
        }
    }
}
