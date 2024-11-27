<?php
namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use PDO;

trait ServiceTrait
{
    // This trait is being used as centralized Trait for services And can be used for fucture developments in future for similar functionality to reduce code redundancy.
    /*
        This ServicesTrait is being used to handled common functionlaity of multiple services and handled in the same trait.
    */
    protected $explodeModel = null;
    protected $modelName = null;

    /*
        Exploding model name as it is comming from each services / controller.
    */
    public function modelExplode($model){
        $this->explodeModel  = explode('\Models', $model);
        $this->modelName =  substr($this->explodeModel[1], 1);
    }
    /*
        Listing down the data.
    */
    public function index(Request $request, $id = null, $model, $obj = null){
        $this->modelExplode($model);      
        $data = $obj ?? $model::all();
        return success(strtolower($this->modelName) . 's.index', ['data' => [                    
            strtolower($this->modelName) => $data,
            'status' => true,
            'message' => $this->modelName . ' Data.',
            'statusCode' => 200,
        ]]);      
    }
    
   
   
    public function create(Request $request, $id = null, $model, $obj = null){
        $this->modelExplode($model);      
        return success(strtolower($this->modelName) . 's.create', ['data' => [                    
            'status' => true,
            'message' => $this->modelName . ' Data.',
            'statusCode' => 200,
        ]]);      
    }
    
    /*
        Storing form data in to database.
    */
    public function store(Request $request, $id = null, $model, $requestArray){        
        $this->modelExplode($model);
        if($requestArray){
            $dataArray = [];
            foreach($requestArray as $key => $list){                    
                $dataArray[$key] = $model::create(
                    $list
                );                    
            }
            $data = $dataArray;
        }else{            
            $data = $model::create(
                $request->all()
            );
        }
        // return redirect()->route(strtolower($this->modelName) . 's.index')->with('success', 'Record Created Successfully');
        return success(strtolower($this->modelName) . 's.index', ['data' => [                    
            strtolower($this->modelName)  => $data,
            'status' => true,
            'message' => $this->modelName . ' Created Successfully',
            'statusCode' => 200,
        ]]);
    }

    /*
        Used To show data.
    */
    public function show(Request $request, $id = null, $model, $search){
        $this->modelExplode($model);
        if($search){            
            $data = $model::where($request->keys()[0], 'LIKE','%'.$request[$request->keys()[0]].'%')->get();                        
            if($data){
                return success(strtolower($this->modelName) . 's.index', ['data' => [                    
                    'status' => true, 
                    'message' =>  $this->modelName . ' Show Search Result.',
                    'data' => $data,            
                    'statusCode' => 200,
                ]]);
            }
            return error(strtolower($this->modelName) . 's.index', ['data' => [                    
                'status' => false, 
                'message' =>  $this->modelName . ' Show Search Result.',
                'data' => $data,       
                'statusCode' => 404,
            ]]);
        }else{            
            $data = $model::find($id);
            if($data){
                return success(strtolower($this->modelName) . 's.index', ['data' => [                    
                    'status' => true, 
                    'message' =>  $this->modelName . ' Show.',
                    'data' => $data,                  
                    'statusCode' => 200,
                ]]);
            }      
            return error(strtolower($this->modelName) . 's.index', ['data' => [                    
                'status' => false, 
                'message' =>  $this->modelName . ' Show Search Result.',
                'data' => $data,       
                'statusCode' => 404,
            ]]);      
        }          
    }

    /*
        To update data in tables for multiple services.
    */
    public function update(Request $request, $id = null, $model, $requestArray){
        $this->modelExplode($model);
        if($requestArray){                
            $dataArray = [];
            foreach($requestArray as $key => $list){          
                $dataArray[$key] = $model::where('id', $list->id)->update(
                    $list
                );     
            }
            $data = $dataArray;
        }else{            
            $data = $model::where('id', $id)->update(
                $request->all()
            );
        }
        return redirect()->route(strtolower($this->modelName) . 's.index')->with('success', 'Record Updated Successfully');
    }

    /*
        To delete data for services dynamically .
    */
    public function destroy(Request $request, $id = null, $model){
        $this->modelExplode($model);
        $data = $model::where('id',(int)$id)->delete();    
        return redirect()->route(strtolower($this->modelName) . 's.index')->with('success', 'Record Deleted Successfully');                
    }

}
