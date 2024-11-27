<?php

namespace App\Http\Controllers;

use App\Models\AsanaCompletedTask;
use App\Models\AsanaDonation;
use App\Models\AsanaDonationField;
use App\Models\AsanaProject;
use App\Models\Configuration;
use App\Services\AsanaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Jobs\FetchProjectsJob;
use App\Models\Permission;
use App\Models\User;

class AsanaController extends Controller
{
    protected $asanaService;
    protected $workSpaceID;
    protected $sandboxProcessedDontationProject;

    public function __construct(AsanaService $asanaService)
    {
        $this->asanaService = $asanaService;
        $this->workSpaceID = env('ASANA_WORKSPACE_ID');
        $this->sandboxProcessedDontationProject = env('SANDBOX_PROCESSED_DDONATIONS_PROJECT');
    }

    public function index(Request $request, $id = null, $model = "\App\Models\AsanaProject"){
        return $this->asanaService->asanaIndex($request, $id, $model);
    }

    public function indexTasks(Request $request, $id = null, $model = "\App\Models\AsanaDonation"){
        return $this->asanaService->asanaIndexTasks($request, $id, $model);
    }

    public function indexTasksFields(Request $request, $id = null, $model = "\App\Models\AsanaDonationField"){
        return $this->asanaService->asanaIndexTasksFields($request, $id, $model);
    }

    // In Case of Admin access
    public function indexComplete()
    {
        $querySearch = null;

        
        // Get all projects with donations
        $projects = AsanaProject::with(['donations.fields'])->get();

        // Calculating bulk E-Waste From given fields.
        // Sum the values of the field with GID 1164605808659656
        $totalSum = $projects->pluck('donations')->flatten()
        ->pluck('fields')->flatten()
        ->filter(function ($field) {
            return $field->gid == '1164605808659656';
        })->sum('value'); // Assuming 'value' is the column you want to sum

        // Paginate donations for each project
        
        foreach ($projects as $project) {
            $project->paginatedDonations = $project->donations()
                ->when($querySearch, function ($query) use ($querySearch) {
                    return $query->where('hit_job_id', 'like', "%$querySearch%");
                })
                ->paginate(5);
            foreach ($project->paginatedDonations as $index => $donation) {
                // make changes to each donation object here
                 $fields = filterFieldsByGids($donation->fields);
                $donation->fields =  $donation->fields;
                $donation->filtered_fields = $fields;
                $project->paginatedDonations[$index] = $donation;
            }
            //exit;
         
        }

        return view('donation.list', compact('projects', 'querySearch', 'totalSum'));
    }

    public function donations(){
        $per_page = donationsPerPage();
        $querySearch = null;
        $user = auth()->user();
        $project_id = getSetting('project_gid');
        $project = AsanaProject::where('gid', $project_id)->with('donations')->first();
        $totalSum = 0;
        $totalDonations = 0;
        $userId = $user->id;
        if ($user->hasRole('Donor')) {
            // Assuming the user is authenticated
            //implication
            //feasible
            //desireable 
            // affordablity

            // Get all projects with donations
            $project = AsanaProject::where('gid', $project_id)->first();
            $donations = ($project != null && $project->donations()) ? $project->donations()->where('user_id', $userId)->orderBy('id', 'desc')->get() : collect();
            
            // Variables to store the total sum of a field and the total donation count
            $totalSum = 0;
            $totalDonations = 0;

            if($donations->count() > 0){
                    // Count total number of donations
                    $totalDonations += $donations->count(); // Add the count of donations to the total
                    
                    // Sum the values of the field with GID 1164605808659656
                    $e_waste_fields = [];
                    $donation_ids = [];
                    // foreach ($donations as $donation) {
                    //     $donation_ids[] = $donation->id;
                    //     //$e_waste_field = $donation->fields->where('gid', '1164605808659656')->first();
                    //     $e_waste_field = AsanaDonationField::where('gid', '1164605808659656')->where('asana_donation_id', $donation->id)->first();
                    //     if($e_waste_field)  {
                    //         $totalSum += $e_waste_field->value;
                    //         //$e_waste_fields[] = $e_waste_field_value;
                    //     } 
                    // }
                    
                    // Paginate donations for the current project
                    $project->paginatedDonations = $project->donations()->orderBy('id', 'desc')
                        ->where('user_id', $userId) // Filter donations for the authenticated user
                        ->paginate($per_page); // Paginate by 5 per page
                    
                    // Process each paginated donation
                    foreach ($project->paginatedDonations as $index => $donation) {
                       
                        $fields = filterFieldsByGids($donation->fields);
                        $donation->filtered_fields = $fields;
                        $project->paginatedDonations[$index] = $donation;
                    }
                    //dd($totalSum);
                
            }
            $donations = $project->paginatedDonations ?? collect(); // Get the first project to pass to the view
            ///dd($donations);

            // Pass the total donations count and total sum to the view
            return view('donation.list', compact('donations', 'querySearch', 'totalSum', 'totalDonations'));

        }elseif($user->hasRole('Backup Role Association')) { 
            return redirect('bakcupAssociation');
        }else{
            $totalDonations = 0;
            $permission = Permission::where('name', 'asana-donationPermissionVerification')->first();
            $hasPermission = \DB::table('role_has_permissions')
            ->whereIn('role_id', $user->roles->pluck('id'))
            ->where('permission_id', $permission->id)
            ->exists();
            if ($hasPermission || $user->hasRole('Super Admin')) {
                // Get all projects with donations
                
                $donations = ($project != null && $project->donations()) ? $project->donations()->orderBy('id', 'desc')->get() : collect();

                
                if($donations->count() > 0){
                    // Paginate donations for each project
                    
                                
                    $totalDonations += $donations->count(); // Add the count of donations to the total
                        
                    // Sum the values of the field with GID 1164605808659656
                    $e_waste_fields = [];
                    $donation_ids = [];
                    // foreach ($donations as $donation) {
                    //     $donation_ids[] = $donation->id;
                    //     $e_waste_field = AsanaDonationField::where('gid', '1164605808659656')->where('asana_donation_id', $donation->id)->first();
                    //     if($e_waste_field)  {
                    //         $totalSum += $e_waste_field->value;
                    //     } 
                    // }
                    
                    // Paginate donations for the current project
                    $project->paginatedDonations = $project->donations()->orderBy('id', 'desc')
                        ->paginate($per_page); // Paginate by 5 per page
                    
                    // Process each paginated donation
                    foreach ($project->paginatedDonations as $index => $donation) {
                    
                        $fields = filterFieldsByGids($donation->fields);
                        $donation->filtered_fields = $fields;
                        $project->paginatedDonations[$index] = $donation;
                    }
                }
                $donations = $project->paginatedDonations ?? collect();
                
                return view('donation.list', compact('donations', 'querySearch', 'totalSum', 'totalDonations'));
            }else{
                $totalDonations = 0;
                $permission = Permission::where('name', 'asana-donationPermissionVerification')->first();
                $hasPermission = \DB::table('role_has_permissions')
                ->whereIn('role_id', $user->roles->pluck('id'))
                ->where('permission_id', $permission->id)
                ->exists();
                if ($hasPermission || $user->hasRole('Super Admin')) {
                    // Get all projects with donations
                    $donations = ($project != null && $project->donations()) ? $project->donations()->orderBy('id', 'desc')->get() : collect(); 
                    if($donations->count() > 0){
                        // Paginate donations for each project
                        
                                    
                        $totalDonations += $donations->count(); // Add the count of donations to the total
                            
                        // Sum the values of the field with GID 1164605808659656
                        $e_waste_fields = [];
                        $donation_ids = [];
                        // foreach ($donations as $donation) {
                        //     $donation_ids[] = $donation->id;
                        //     $e_waste_field = AsanaDonationField::where('gid', '1164605808659656')->where('asana_donation_id', $donation->id)->first();
                        //     if($e_waste_field)  {
                        //         $totalSum += $e_waste_field->value;
                        //     } 
                        // }
                        
                        // Paginate donations for the current project
                        $project->paginatedDonations = $project->donations()->orderBy('id', 'desc')
                            ->paginate($per_page); // Paginate by 5 per page
                        
                        // Process each paginated donation
                        foreach ($project->paginatedDonations as $index => $donation) {
                        
                            $fields = filterFieldsByGids($donation->fields);
                            $donation->filtered_fields = $fields;
                            $project->paginatedDonations[$index] = $donation;
                        }
                    }
                    $donations = $project->paginatedDonations ?? collect();
                    return view('donation.list', compact('donations', 'querySearch', 'totalSum', 'totalDonations'));
                }else{
                    // The role does not have the permission
                }
            }
        }
    }

    public function donorDonations($id){
        $per_page = donationsPerPage();
        $querySearch = null;
        $user = User::find($id);
        if ($user->hasRole('Donor')) {
            // Assuming the user is authenticated
            $userId = $user->id;

            // Get all projects with donations
            $project_id = getSetting('project_gid');
            
            $project = AsanaProject::where('gid', $project_id)->first();
            $donations = ($project != null && $project->donations()) ? $project->donations()->where('user_id', $id)->get() : collect();
            
            // Variables to store the total sum of a field and the total donation count
            $totalSum = 0;
            $totalDonations = 0;

            if($donations->count() > 0){
                    // Count total number of donations
                    $totalDonations += $donations->count(); // Add the count of donations to the total
                    
                    // Sum the values of the field with GID 1164605808659656
                    $e_waste_fields = [];
                    $donation_ids = [];
                    foreach ($donations as $donation) {
                        $donation_ids[] = $donation->id;
                        //$e_waste_field = $donation->fields->where('gid', '1164605808659656')->first();
                        $e_waste_field = AsanaDonationField::where('gid', '1164605808659656')->where('asana_donation_id', $donation->id)->first();
                        if($e_waste_field)  {
                            $totalSum += $e_waste_field->value;
                            //$e_waste_fields[] = $e_waste_field_value;
                        } 
                    }
                    
                    // Paginate donations for the current project
                    $project->paginatedDonations = $project->donations()
                        ->where('user_id', $userId) // Filter donations for the authenticated user
                        ->orderBy('id', 'desc')
                        ->paginate($per_page); // Paginate by 5 per page
                    
                    // Process each paginated donation
                    foreach ($project->paginatedDonations as $index => $donation) {
                       
                        $fields = filterFieldsByGids($donation->fields);
                        $donation->filtered_fields = $fields;
                        $project->paginatedDonations[$index] = $donation;
                    }
                    //dd($totalSum);
                
            }
            $donations = $project->paginatedDonations ?? collect(); // Get the first project to pass to the view
            ///dd($donations);

            // Pass the total donations count and total sum to the view
            return view('donation.list', compact('donations', 'querySearch', 'totalSum', 'totalDonations'));

        } else {
            return redirect()->back()->withErrors(['donor' => trans('Donor not found.')]); 
        }
    }

    public function getWorkspaces()
    {
        return view('asana.index', ['data' => response()->json($this->asanaService->getWorkspaces())]);
    }

    public function getProjects()
    {
        return view('asana.index', ['data' => response()->json($this->asanaService->getProjects($this->workSpaceID))]);
    }

    //  step 1 is to fettch all the tasks from the project...
    public function getTasks()
    {
        return view('asana.index', ['data' => response()->json($this->asanaService->getTasks($this->sandboxProcessedDontationProject))]);
    }

    public function getSubtasks($taskId)
    {
        return view('asana.index', ['data' => response()->json($this->asanaService->getSubtasks($taskId))]);
    }

    public function getCompletedTasks()
    {
        $completedTasks = $this->asanaService->getCompletedTasksFromProject($this->sandboxProcessedDontationProject);
        // dd($completedTasks);
        $allTasks = [];
        $enum = [];
        $multiEnum = [];
        $text = [];
        $people = [];
        $date = [];
        $number = [];
        foreach($completedTasks as $key => $taskInfo){
            $task = $this->asanaService->getCustomFields($taskInfo['gid']);
            $allTasks[] = $this->asanaService->getCustomFields($taskInfo['gid']);
            $allTasks[$key]['data']['completed'] = $taskInfo['completed']; 
            $allTasks[$key]['data']['name'] = $taskInfo['name']; 
        }
       
        return view('asana.completed_tasks', [
            'tasks' => $allTasks,
            'project' => $this->sandboxProcessedDontationProject
        ]);
    }

    public function getTaskDetails($taskId)
    {
        $task = $this->asanaService->getTaskDetails($taskId);
        $stories = $this->asanaService->getTaskStories($taskId);
        return view('asana.custom_fields', ['data' => $task['data'], 'stories' => $stories['data']]);
    }

    public function getCustomFields($taskId)
    {
        $task = $this->asanaService->getCustomFields($taskId);
        $enum = [];
        $multiEnum = [];
        $text = [];
        $people = [];
        $date = [];
        $number = [];
        foreach($task['data']['custom_fields'] as $data){
            // echo ($data['type']) . '<br>';
            if($data['type'] == 'enum'){
                $enum[] = $data;
            }
            if($data['type'] == 'multi_enum'){
                $multiEnum[] = $data;
            }
            if($data['type'] == 'text'){
                $text[] = $data;
            }
            if($data['type'] == 'people'){
                $people[] = $data;
            }
            if($data['type'] == 'date'){
                $date[] = $data;
            }
            if($data['type'] == 'number'){
                $number[] = $data;
            }
        }
        return view('asana.index', ['data' => $task['data']]);
    }
    
    public function search(Request $request)
    {
        $query = Crypt::encryptString($request->input('query'));
        $results = [];

        $fields = AsanaDonationField::
                                            orWhere('value', 'LIKE', "%{$query}%")
                                            ->get();
        $getdata = AsanaDonationField::where('value', 95222)->get();
        // Search Asana projects
        $projects = AsanaProject::where('name', 'LIKE', "%{$query}%")->get();
        foreach ($projects as $project) {
            $tasks = AsanaDonation::where('asana_project_id', $project->id)->get();
            foreach ($tasks as $task) {
                $fields = AsanaDonationField::where('asana_donation_id', $task->id)
                                            // ->orwhere('name', 'LIKE', "%{$query}%")
                                            ->orWhere('value', 'LIKE', "%{$query}%")
                                            ->get();
                                            dd($task->id, $fields, $query);
                foreach ($fields as $field) {
                    $results[] = [
                        'project_name' => $project->name,
                        'task_name' => $task->title,
                        'custom_field_name' => $field->name,
                        'custom_field_value' => $field->value,
                    ];
                }
            }
        }
        return response()->json($results);
    }

    public function syncDonations(){
        $workspaceId = env('ASANA_WORKSPACE_ID');
        //$sandboxProjectId = env('SANDBOX_PROCESSED_DDONATIONS_PROJECT');
        $sandboxProjectId = getSetting('project_gid');

        // Dispatch the job to fetch projects
        //FetchProjectsJob::dispatch($workspaceId, $sandboxProjectId);

    }

    public function donationsSync($id){
        //$workspaceId = env('ASANA_WORKSPACE_ID');
        //$sandboxProjectId = getSetting('project_gid');

        // Dispatch the job to fetch projects
        //FetchProjectsJob::dispatch($workspaceId, $sandboxProjectId, $type = 'donorDonationSync', $id);
        //return redirect('/donors/donations/'.$id)->with('success', 'Donations synced successfully. It may take a while to sync. Please wait and reload the page.');
        return   $this->asanaService->donationsSync($type = 'donorDonationSync', $id);
        
    }
    public function syncAllDonations(Request $request){
        set_time_limit(0);
        //$workspaceId = env('ASANA_WORKSPACE_ID');
        //$sandboxProjectId = getSetting('project_gid');

        // Dispatch the job to fetch projects
        //FetchProjectsJob::dispatch($workspaceId, $sandboxProjectId, $type = 'donorDonationSync', $id);
        //return redirect('/donors/donations/'.$id)->with('success', 'Donations synced successfully. It may take a while to sync. Please wait and reload the page.');
        if($request->query('key') != null) {
            $key = $request->query('key');
            $cron_job_key = getSetting('cron_job_key') ?? 'invlid-key';
            if($key == $cron_job_key) {
                return $this->asanaService->syncAllDonationsAuto($type = 'donorDonationSync', $request);    
            } else {
                return response()->json(['status' => false, 'message' => 'invalid key'], 200);
            }
            
        }
        return $this->asanaService->syncAllDonations($type = 'donorDonationSync', $request);
        
    }
    public function getTechDonorIds() {
        $users = User::role('Donor')->orderBy('id', 'desc')->get();
        $tech_donor_ids = $users->pluck('tech_donor_customer_id')->filter()->toArray();
        return $tech_donor_ids;
    }
    public function getSyncDonationPages(Request $request){
        $total_pages = numPagesDonations($this->getTechDonorIds());
        return response()->json(['status' => true, 'pages' => $total_pages], 200);
    }
    public function updateAllDonations(){
        //$workspaceId = env('ASANA_WORKSPACE_ID');
        //$sandboxProjectId = getSetting('project_gid');

        // Dispatch the job to fetch projects
        //FetchProjectsJob::dispatch($workspaceId, $sandboxProjectId, $type = 'donorDonationSync', $id);
        //return redirect('/donors/donations/'.$id)->with('success', 'Donations synced successfully. It may take a while to sync. Please wait and reload the page.');
        return   $this->asanaService->updateAllDonations($type = 'donorDonationUpdate');
        
    }

    public function donationsUpdate($id){
        //$workspaceId = env('ASANA_WORKSPACE_ID');
        //$sandboxProjectId = getSetting('project_gid');

        // Dispatch the job to fetch projects
        //FetchProjectsJob::dispatch($workspaceId, $sandboxProjectId, $type = 'donorDonationUpdate', $id);
        //return redirect('/donors/donations/'.$id)->with('success', 'Donations synced successfully. It may take a while to sync. Please wait and reload the page.');
        return   $this->asanaService->donationsUpdate($type = 'donorDonationUpdate', $id);
    }
    public function clearDonations(Request $request, $id)
{
    // Find all donations related to the given $id
    $donations = AsanaDonation::where('user_id', $id)->get();

    // Check if donations exist for the given id
    if ($donations->isEmpty()) {
        return redirect()->back()->with('error', "Donor doesn't have any donations.");
    }

    // Loop through each donation and delete related custom fields and the donation itself
    foreach ($donations as $donation) {
        // Delete all related custom fields (AsanaDonationField)
        $donation->fields()->delete();

        // Delete the donation itself
        $donation->delete();
    }

    // Return success response
    return redirect()->back()->with('success', "Donations has been deleted successfully.");
}
}
