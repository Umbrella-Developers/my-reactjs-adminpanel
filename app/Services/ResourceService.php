<?php

namespace App\Services;

use App\Models\AsanaProject;
use App\Traits\ServiceTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * Class ResourceService.
 */
class ResourceService
{
    /*
        Imported ServiceTrait File 
    */ 
    use ServiceTrait;

    /*
        resourceIndex function is showing listed data. ServiceTrait where all Services are handled. In index function
    */ 
    public function resourceIndex(Request $request, $id, $model){       
        $querySearch = null;

        // Get all projects with donations
        $projects = AsanaProject::with(['donations.fields'])->get();
        
        // Paginate donations for each project
        foreach ($projects as $project) {
            $project->paginatedDonations = $project->donations()
                ->when($querySearch, function ($query) use ($querySearch) {
                    return $query->where('hit_job_id', 'like', "%$querySearch%");
                })
                ->paginate(5);
        }

        return view('asana.indexComplete', compact('projects', 'querySearch'));
    }
    
    /*
        resourceStore function is storing  data. ServiceTrait where all Services are handled. In store function
    */ 
    public function resourceStore(Request $request, $id, $model){
        $requestArray = null;
        $rules = [
            'title' => 'required',
            'description' => 'required',   
        ];
    
        // Validate the request using the helper function
        validateRequest($request, $rules); 
        hasSingleImageFile($request);        

        return $this->store($request, $id, $model, $requestArray);
    }

    /*
        resourceShow function is showing single  data. ServiceTrait where all Services are handled. In show function
    */ 
    public function resourceShow(Request $request, $id, $model){
        if(empty($request)){
            return $this->show($request, $id, $model, $search = true);
        }
        return $this->show($request, $id, $model, $search = false);    
    }

    /*
        resourceDestroy function is deleting data. ServiceTrait where all Services are handled. In destroy function
    */ 
    public function resourceDestroy(Request $request, $id, $model){
        return $this->destroy($request, $id, $model);        
    }

    /*
        resourceUpdate function is update data. ServiceTrait where all Services are handled. In update function
    */ 
    public function resourceUpdate(Request $request, $id, $model){
            $requestArray = null;
            $rules = [
                'title' => 'required',
                'description' => 'required',   
            ];
        
            // Validate the request using the helper function
            validateRequest($request, $rules); 
            hasSingleImageFile($request);
            return $this->update($request, $id, $model, $requestArray);       
    }
}
