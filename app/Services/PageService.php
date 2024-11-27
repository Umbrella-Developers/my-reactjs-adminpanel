<?php

namespace App\Services;

use App\Models\Page;
use App\Traits\ServiceTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/*
    Store, Destroy, Update, Index, Show.
    Note. Service is being created for PageController. 
    And logic is being handled in PageService. And base structure is given in PageController.
*/
class PageService
{
    /*
        Imported ServiceTrait File 
    */ 
    use ServiceTrait;

    /*
        pageIndex function is showing listed data. ServiceTrait where all Services are handled. In index function
    */ 
    public function pageIndex(Request $request, $id, $model){       
        return $this->index($request, $id, $model);
    }
    
    /*
        pageStore function is storing  data. ServiceTrait where all Services are handled. In store function
    */ 
    public function pageStore(Request $request, $id, $model){
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
        pageShow function is showing single  data. ServiceTrait where all Services are handled. In show function
    */ 
    public function pageShow(Request $request, $id, $model){
        if(empty($request)){
            return $this->show($request, $id, $model, $search = true);
        }
        return $this->show($request, $id, $model, $search = false);    
    }

    /*
        pageDestroy function is deleting data. ServiceTrait where all Services are handled. In destroy function
    */ 
    public function pageDestroy(Request $request, $id, $model){
        return $this->destroy($request, $id, $model);        
    }

    /*
        pageUpdate function is update data. ServiceTrait where all Services are handled. In update function
    */ 
    public function pageUpdate(Request $request, $id, $model){
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
