<?php

namespace App\Services;

use App\Models\AsanaDonation;
use App\Models\AsanaDonationField;
use App\Models\AsanaProject;
use GuzzleHttp\Client;
use App\Jobs\FetchProjectsJob;
use Illuminate\Http\Request;
use App\Traits\ServiceTrait;



use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Jobs\FetchTasksJob;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use App\Models\Configuration;
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;

/**
 * Class AsanaService.
 */
class AsanaService
{
    use ServiceTrait;

    protected $client;
    protected $workspaceId;
    protected $projectId;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://app.asana.com/api/1.0/',
            'headers' => [
                'Authorization' => 'Bearer ' . env('ASANA_ACCESS_TOKEN'),
                'Content-Type' => 'application/json'
            ]
        ]);
        $this->workspaceId = env('ASANA_WORKSPACE_ID');
        $this->projectId = getSetting('project_gid');
    }

    public function asanaIndex(Request $request, $id, $model){
        return $this->index($request, $id, $model);
    }

    public function asanaIndexTasks(Request $request, $id, $model){
        return $this->index($request, $id, $model);
    }

    public function asanaIndexTasksFields(Request $request, $id, $model){
        return $this->index($request, $id, $model);
    }

    public function getWorkspaces()
    {
        $response = $this->client->get('workspaces');
        return json_decode($response->getBody()->getContents(), true);
    }

    public function getProjects($workspaceId)
    {
        $response = $this->client->get("workspaces/{$workspaceId}/projects");
        return json_decode($response->getBody()->getContents(), true);
    }

    public function getTasks($projectId)
    {
        $workspaceId = env('ASANA_WORKSPACE_ID');
        $projectId = env('SANDBOX_PROCESSED_DDONATIONS_PROJECT');

        // Dispatch the job to fetch projects
        FetchProjectsJob::dispatch($workspaceId, $projectId);

    }

    public function getSubtasks($taskId)
    {
        $response = $this->client->get("tasks/{$taskId}/subtasks");
        return json_decode($response->getBody()->getContents(), true);
    }

    public function getCustomFields($taskId)
    {
        $response = $this->client->get("tasks/{$taskId}?opt_fields=custom_fields");
        // dd($response->getBody()->getContents());
        return json_decode($response->getBody()->getContents(), true);
    }

    public function getCompletedTasksFromProject($projectId)
    {
        $tasks = [];
        $hasMore = true;
        $url = "projects/{$projectId}/tasks";
        $params = [
            'opt_fields' => 'name,completed',
            'limit' => 100 // Adjust limit as needed
        ];

        while ($hasMore) {
            $response = $this->client->get($url, ['query' => $params]);
            $data = json_decode($response->getBody()->getContents(), true);

            foreach ($data['data'] as $task) {
                if ($task['completed']) {
                    $tasks[] = $task;
                }
            }

            if (isset($data['next_page'])) {
                $hasMore = $data['next_page']['has_more'];
                if ($hasMore) {
                    $url = $data['next_page']['uri'];
                }
            } else {
                $hasMore = false;
            }
        }

        return $tasks;
    }

    public function getTaskDetails($taskId)
    {
        $response = $this->client->get("tasks/{$taskId}?opt_fields=name,custom_fields,projects");
        return json_decode($response->getBody()->getContents(), true);
    }

    public function getTaskStories($taskId)
    {
        $response = $this->client->get("tasks/{$taskId}/stories");
        return json_decode($response->getBody()->getContents(), true);
    }
    // sync donations
    public function donationsSync($type, $id) 
    {
        
        $resp = $this->getAsanaProjects($type, $id);
        if($resp){  
            return redirect('/donors/donations/'.$id)->with('success', 'Donations synced successfully.');
        } else {
            return redirect()->back()->with('error', 'Something went wrong. Please try again.');
        }
    }
    public function syncAllDonations($type, $request) 
    {
        $project_gid = getSetting('project_gid');
        //$project = AsanaProject::where('gid', $project_gid)->first();
        $resp = $this->getAllAsanaDonations($project_gid, $type, $request);
        if($resp){  
            //return redirect('/donations')->with('success', 'Donations synced successfully.');
            return response()->json(['status' => true, 'message' => 'donations synced successfully.'], 200);
        } else {
            return redirect()->back()->with('error', 'Something went wrong. Please try again.');
        }
    }
    public function syncAllDonationsAuto($type, $request) 
    {
        $project_gid = getSetting('project_gid');
        //$project = AsanaProject::where('gid', $project_gid)->first();
        $resp = $this->getAllAsanaDonations($project_gid, $type, $request);
        if($resp){  
            return response()->json(['status' => true, 'message' => 'donations synced successfully.'], 200);
        } else {
            return response()->json(['status' => false, 'message' => 'Error occured'], 200);
        }
    }
    public function updateAllDonations($type) 
    {
        $project_gid = getSetting('project_gid');
        //$project = AsanaProject::where('gid', $project_gid)->first();
        $resp = $this->getAllAsanaDonations($project_gid, $type);
        if($resp){  

            return redirect('/donations')->with('success', 'Donations updated successfully.');
        } else {
            return redirect()->back()->with('error', 'Something went wrong. Please try again.');
        }
    }
    public function getTechDonorIds() {
        $users = User::role('Donor')->orderBy('id', 'desc')->get();
        $tech_donor_ids = $users->pluck('tech_donor_customer_id')->filter()->toArray();
        return $tech_donor_ids;
    }
    public function getAllAsanaDonations($project_gid, $type = null, $request)
    {
        $page = (int) $request->query('page') ?? 1;
        
        $tech_donor_ids_arry = $this->getTechDonorIds();
        $tech_donor_ids = paginateCustomArray($tech_donor_ids_arry, $page);
        
        $taskData = [];
        $paginationLimit = 100; // Limit of items per page
         // Track the last task's creation time
        //$dueAfterDate = '2024-10-11'; // Filter tasks due after this date
        $donor_tasks = [];
        $tasks = [];
        $sync_days = getSetting('sync_days') ?? 100;
        if(count($tech_donor_ids) > 0){
            foreach ($tech_donor_ids as $k => $tech_donor_id) {
                $lastCreatedAt = null;
                $user = User::where('tech_donor_customer_id', $tech_donor_id)->first();
                // If user doesn't exist, skip the process
                if (!$user) {
                    continue;
                }
                $donor_tasks[$tech_donor_id]['tasks'] = [];
                $donor_tasks[$tech_donor_id]['user'] = $user;
                $donor_tasks[$tech_donor_id]['tech_donor_id'] = $tech_donor_id;
                $hasMorePages = true;
                $starting_date = Carbon::now()->subDays($sync_days)->toIso8601String();
                //$ending_date = Carbon::now()->subDays(30)->toIso8601String();
                //dd($starting_date, $ending_date);
                do {
                    // load user first else continue to next record.
                    
                    // Set up query parameters for filtering and pagination
                    $options = [
                        'query' => [
                            'limit' => $paginationLimit, // Limit results per page
                            'custom_fields.1199173512699290.value' => $tech_donor_id, // tech_donor_customer_id
                            'custom_fields.1206123470176889.value' => 1206123477157607, // Donation Finalization Stage
                            'projects.any' => $project_gid,
                            'opt_fields' => 'created_at,name,resource_type,resource_subtype,custom_fields', // Specify fields to include in the response
                            'sort_by' => 'created_at', // Sort by creation time for manual pagination
                            // 'due_after' => $dueAfterDate, // Filter tasks due after this date
                            'sort_ascending' => true,
                            'created_at.after' => $starting_date,
                            //'created_at.before' => $ending_date
                        ],
                    ];
                    
                    // If we have the last task's creation time, add a filter to fetch tasks created after it
                    if ($lastCreatedAt) {
                        $formattedDate = Carbon::parse($lastCreatedAt)->addSecond()->toIso8601String();
                        //$options['query']['created_at.before'] = $formattedDate;
                        
                        $options['query']['created_at.after'] = $formattedDate;
                    }

                    
                    // Make the request to Asana's API
                    $response = $this->client->get("workspaces/11605158433449/tasks/search", $options);
                    $data = json_decode($response->getBody()->getContents(), true);
                    
                    // Log::error('Project Options: ' . serialize($options));
                    // $last_item = end($data['data']);
                    // if(isset($data['data'][0])) {
                    //     Log::error('Task Response: ' . serialize($data['data'][0]));
                    // }
                    
                    // Merge the current page data with the taskData array
                    $donor_tasks[$tech_donor_id]['tasks'] = array_merge($donor_tasks[$tech_donor_id]['tasks'], $data['data']);
                    $taskData = array_merge($taskData, $data['data']); // not being used, just in case, it is needed.
                    

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
                
            }
        }
        //dd($tech_donor_ids, $taskData);
        // Process the filtered tasks with ProcessTaskJob
        if(count($taskData) > 0) {
            $ret =  $this->storeBulkDonations($donor_tasks, $project_gid,  $type); // Pass the necessary data to ProcessTaskJob
            return true;
        } else {
            return false;
        }
    }
    /**
     * $donor_tasks: list of donors with tech donor id and tasks.
     */
    public function storeBulkDonations($donor_tasks, $project_id, $type = null)
    {
        try {
            if(count($donor_tasks) > 0 ){
                $getAsanaProjectID = AsanaProject::where('gid', $project_id)->first();
                
                foreach ($donor_tasks as $item) {
                    $tasks = $item['tasks'];
                    $user = $item['user'];
                    $tech_donor_id = $item['tech_donor_id'];
                    
                    if(count($tasks) > 0 ) {
                        foreach ($tasks as $task) {
                            // Fetch the project ID
                            $techDonorCustomerId = null; // To store tech_donor_customer_id from custom fields
                            //Log::error('Task Data: ' . serialize($task['custom_fields']) );
                            // Fetch user details from custom fields
                            foreach ($task['custom_fields'] as $value) {
                                $displayValue = $value['display_value'] ?? 0;
            
                                if ($value['gid'] == '1199173512699290') {
                                    $techDonorCustomerId = $displayValue; // Store the tech_donor_customer_id
                                }
                            }
            
                            // Handle different types
                            if ($type === 'donorDonationSync') {
                                
                                $this->handleDonorDonationSync($task, $getAsanaProjectID, $user);
                            } elseif ($type === 'donorDonationUpdate') {
                                $this->handleDonorDonationUpdate($task, $getAsanaProjectID, $user);
                            }
                            
                            Log::error('Task coutn: '. $tech_donor_id .''. count($tasks));
                        }
                    }
                }  
                return true;  
            }
            
            
            
        } catch (\Exception $e) {
            Log::error('Error Soring Donation: ' . $e->getMessage());
        }
        return false;
    }    
        
        
    public function donationsUpdate($type, $id) 
    {
        
        $resp = $this->getAsanaProjects($type, $id);
        if($resp){  
            return redirect('/donors/donations/'.$id)->with('success', 'Donations updated successfully.');
        } else {
            return redirect()->back()->with('error', 'Something went wrong. Please try again.');
        }
    }
    public function getAsanaProjects( $type, $user_id)
    {
        try {
            // Fetch the specific project using projectId
            $response = $this->client->get("projects/{$this->projectId}");
            $projectData = json_decode($response->getBody()->getContents(), true);

            
            // Check if project data is retrieved successfully
            if (!isset($projectData['data'])) {
                Log::error('Project data not found for GID: ' . $this->projectId);
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
            //Log::error('Project Responsed: ' . serialize($projectData['data']) . ' for GID: ' . $this->projectId);
            // Dispatch FetchTasksJob for this project with the correct type
            $user = User::where('id', $user_id)->first();
            $tech_donor_id = $user->tech_donor_customer_id;
            //FetchTasksJob::dispatch($project['gid'], $this->type, $this->userId, $tech_donor_id);
            return $this->getAsanaDonations($project['gid'], $type, $user_id, $tech_donor_id);

        } catch (\Exception $e) {
            Log::error('Error fetching projects: ' . $e->getMessage());
            $error = $e->getMessage();
            
            // redirect with error
        }
        return redirect()->back()->with('error', 'Something went wrong.');
    }
    
    public function getAsanaDonations($project_gid, $type = null, $user_id, $tech_donor_id)
    {
        
        $fieldArray = [];
        $configurationsData = Configuration::where('type', 'encryption')->where('value', '1')->get();
        foreach ($configurationsData as $data) {
            $fieldArray[] = $data->name;
        }
        $taskData = [];
        $hasMorePages = true;
        $paginationLimit = 100; // Limit of items per page
        $lastCreatedAt = null; // Track the last task's creation time
        $dueAfterDate = '2024-10-11'; // Filter tasks due after this date
        do {
            // Set up query parameters for filtering and pagination
            $options = [
                'query' => [
                    'limit' => $paginationLimit, // Limit results per page
                    'custom_fields.1199173512699290.value' => $tech_donor_id, // tech_donor_customer_id
                    'custom_fields.1206123470176889.value' => 1206123477157607, // Donation Finalization Stage
                    'projects.any' => getSetting('project_gid'),
                    'opt_fields' => 'created_at,name,resource_type,resource_subtype,custom_fields', // Specify fields to include in the response
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
            $response = $this->client->get("workspaces/11605158433449/tasks/search", $options);
            $data = json_decode($response->getBody()->getContents(), true);
            
            // Log::error('Project Options: ' . serialize($options));
            // $last_item = end($data['data']);
            // if(isset($data['data'][0])) {
            //     Log::error('Task Response: ' . serialize($data['data'][0]));
            // }
            

            // Merge the current page data with the taskData array
            $taskData = array_merge($taskData, $data['data']);

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
        
        // Process the filtered tasks with ProcessTaskJob
        if(count($taskData) > 0) {
            $ret =  $this->storeDonation($taskData, $project_gid,  $type, $user_id, $tech_donor_id); // Pass the necessary data to ProcessTaskJob
            return true;
        } else {
            return false;
        }
        
        
        
        
    }

    public function storeDonation($tasks, $project_id, $type = null, $user_id, $tech_donor_id)
    {
        try {
            
            foreach ($tasks as $task) {
                
                
                // Fetch the project ID
                $getAsanaProjectID = AsanaProject::where('gid', $project_id)->first();

                $techDonorCustomerId = null; // To store tech_donor_customer_id from custom fields
                //Log::error('Task Data: ' . serialize($task['custom_fields']) );
                // Fetch user details from custom fields
                foreach ($task['custom_fields'] as $value) {
                    $displayValue = $value['display_value'] ?? 0;

                    if ($value['gid'] == '1199173512699290') {
                        $techDonorCustomerId = $displayValue; // Store the tech_donor_customer_id
                    }
                }

                // Verify tech_donor_customer_id against the users table
                $user = User::where('id', $user_id)->where('tech_donor_customer_id', $techDonorCustomerId)->first();
                // If user doesn't exist, skip the process
                if (!$user) {
                    return;
                }

                // Handle different types
                if ($type === 'donorDonationSync') {
                    $this->handleDonorDonationSync($task, $getAsanaProjectID, $user);
                } elseif ($type === 'donorDonationUpdate') {
                    $this->handleDonorDonationUpdate($task, $getAsanaProjectID, $user);
                }
            }
            return true;
            
        } catch (\Exception $e) {
            Log::error('Error Soring Donation: ' . $e->getMessage());
        }
        return false;
    }
    

    private function handleDonorDonationSync($task, $asanaProjectID, $user)
    {
        try {
            
            $existingDonations = AsanaDonation::where('gid', $task['gid'])->first();
            
            // Store donation only if it doesn't already exist
            if (!$existingDonations) {
                $task_gid = $task['gid'];
                $task_name = $task['name'];
                $donationData = [
                    'gid' => $task_gid,
                    'title' => $task_name,
                    'hit_job_id' => 0, // Update this accordingly if needed
                    'asana_project_id' => $asanaProjectID->id,
                    'status' => 1,
                    'user_id' => (int)$user->id, // Store the user ID
                    'donation_created_at' => $task['created_at'], // Store the user ID
                ];
                $newDonation = AsanaDonation::create($donationData);
                $this->storeCustomFields($task, $newDonation['id']);
                return true;
            }
        } catch (\Exception $e) {
            Log::error('Error Creating Donation: ' . $e->getMessage());
        }
        return false;
    }
    
    private function handleDonorDonationUpdate($task, $asanaProjectID, $user)
    {
        try {
            $existingDonations = AsanaDonation::where('gid', $task['gid'])->first();
            // Proceed if the donation exists
            if ($existingDonations) {
                // Update title if changed
                if ($existingDonations->title !== $task['name']) {
                    $existingDonations->title = $task['name'];
                }

                // Fetch and update custom fields if they match
                $this->updateCustomFields($task, $existingDonations, $user->tech_donor_customer_id);
                $existingDonations->save();
                return true;
            }
        } catch (\Exception $e) {
            Log::error('Error updating Donation: ' . $e->getMessage());
        }
        return false;
    }
    private function storeCustomFields($task, $donationID)
    {
        $donationFieldsToInsert = [];
        if(isset($task['custom_fields']) && count($task['custom_fields']) > 0) {
            foreach ($task['custom_fields'] as $value) {
                $displayValue = $value['display_value'] ?? 0;

                $donationFieldsToInsert[] = [
                    'gid' => $value['gid'],
                    'name' => $value['name'],
                    'value' => Crypt::encryptString($displayValue), // Encrypt if needed
                    //'field_object' => Crypt::encryptString(json_encode([])), // Adjust based on field type
                    'field_type' => $value['type'],
                    'asana_donation_id' => $donationID,
                    'status' => '1',
                ];
            }
            // Insert donation fields
            AsanaDonationField::insert($donationFieldsToInsert);
        }
    }

    private function updateCustomFields($task, $asanaDonation, $techDonorCustomerId)
    {
        // Bulk delete existing fields for the donation
        AsanaDonationField::where('asana_donation_id', $asanaDonation->id)->delete();
        //Prepare bulk insert data
        $newFields = [];
        
        foreach ($task['custom_fields'] as $value) {
            $displayValue = $value['display_value'] ?? 0;
            $newFields[] = [
                'gid' => $value['gid'],
                'name' => $value['name'],
                'value' => Crypt::encryptString($displayValue), // Encrypt value
                'field_object' => Crypt::encryptString(json_encode([])), // Adjust based on field type
                'field_type' => $value['type'],
                'asana_donation_id' => $asanaDonation->id,
                'status' => '1',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        
        //  Bulk insert new fields
        if (!empty($newFields)) {
            AsanaDonationField::insert($newFields); // Bulk insert in one query
        }    
    }

    

    
}
