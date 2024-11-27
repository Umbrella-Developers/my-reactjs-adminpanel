<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AsanaDonation;
use App\Models\AsanaDonationField;
use App\Models\AsanaProject;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Crypt;

class AsanaSearchController extends Controller
{
    public function searchTasks(Request $request)// public function searchTasks(Request $requst)
    {
        $client = new Client([
            'base_uri' => 'https://app.asana.com/api/1.0/',
            'headers' => [
                'Authorization' => 'Bearer ' . env('ASANA_ACCESS_TOKEN'),
                'Content-Type' => 'application/json'
            ]
        ]);

        $projectId = $request->input('project_id');
        $searchTerm = $request->input('search_term');
        $fieldArray = $request->input('field_array', []); // Array of field GIDs to search

        // Fetch tasks for the specified project
        $tasks = $this->getTasksForProject($projectId, $client);

        // Filter tasks based on search term and custom fields
        $filteredTasks = $this->filterTasks($tasks, $searchTerm, $fieldArray);

        // Return the filtered tasks as JSON
        return response()->json($filteredTasks);
    }

    private function getTasksForProject($projectId, $client)
    {
        $response = $client->get("projects/{$projectId}/tasks");
        return json_decode($response->getBody()->getContents(), true);
    }

    private function filterTasks($tasks, $searchTerm, $fieldArray)
    {
        $filteredTasks = [];

        foreach ($tasks['data'] as $task) {
            $taskDetails = $this->getTaskDetails($task['gid']);
            foreach ($taskDetails['data']['custom_fields'] as $field) {
                if (in_array($field['gid'], $fieldArray) && strpos(strtolower($field['display_value']), strtolower($searchTerm)) !== false) {
                    $filteredTasks[] = $taskDetails['data'];
                    break;
                }
            }
        }

        return $filteredTasks;
    }

    private function getTaskDetails($taskId, $client)
    {
        $response = $client->get("tasks/{$taskId}");
        return json_decode($response->getBody()->getContents(), true);
    }

    public function searchCheck(Request $request){
        $query = '';
        $querySearch = $request->input('query');
        // Adjust the query to search for projects, donations, or fields based on your requirements
        $projects = AsanaProject::whereHas('donations.fields', function ($q) use ($query) {
            // $q->where('gid', '215976511602589')->where('name', 'LIKE', "%{$query}%")->orWhere('value', 'LIKE', "%{$query}%");
        })->get();
        return view('asana.indexComplete', compact('projects', 'querySearch'));
    }
}
