<?php

namespace App\Services;

use App\Models\Configuration;
use App\Traits\ServiceTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/*
    Store, Destroy, Update, Index, Show.
    Note. Service is being created for ConfigurationController. 
    And logic is being handled in ConfigurationService. And base structure is given in ConfigurationController.
*/
class ConfigurationService
{
    /*
        Imported ServiceTrait File 
    */ 
    use ServiceTrait;
    /*
        configurationIndex function is showing listed data. ServiceTrait where all Services are handled. In index function
    */ 
    public function configurationIndex(Request $request, $id, $model){        
        // $obj = $model::select('name')->where('type' , '!=', 'encryption')->get()->groupBy('type');
        $obj = $model::where('type' , '!=', 'encryption')->get()->groupBy('type');
        if($obj){
            return $this->index($request, $id, $model, $obj);
        }else{
            return $this->index($request, $id, $model, $obj = null);
        }
    }

    /*
        configurationShow function is showing single  data. ServiceTrait where all Services are handled. In show function
    */ 
    public function configurationShow(Request $request, $id, $model){   
        if(empty($request)){
            return $this->show($request, $id, $model, $search = true);
        }
        return $this->show($request, $id, $model, $search = false);        
    }

    public function configurationEdit($id){
        $configurations = Configuration::find($id);     
        return success('configurations.index', ['data' => [                    
            'status' => true, 
            'message' => 'Configuration information fetched to edit.',
            'configurations' => $configurations,
            'statusCode' => 200,
        ]]);
    }

    /*
        configurationStore function is storing  data. ServiceTrait where all Services are handled. In store function
    */ 
    public function configurationStore(Request $request)
    {
        $configurationData = $request->only([
            'key', 'value', 'title', 'description', 'input_type', 'editable', 'weight', 'params', 'order'
        ]);

        $configuration = Configuration::create($configurationData);

        return success('configurations.index', [
            'data' => [
                'status' => true,
                'message' => 'Configuration Saved Successfully.',
                'statusCode' => 200,
            ]
        ]);
    }

    /*
        configurationUpdate function is update data. ServiceTrait where all Services are handled. In update function
    */ 
    public function configurationUpdate(Request $request, $id){
        $data = $request->only([
            'key', 'value', 'title', 'description', 'input_type', 'editable', 'weight', 'params', 'order'
        ]);

        Configuration::where('id', $id)->update($data);

        return success('configurations.index', ['data' => [                    
            'status' => true,
            'message' => 'Configuration Updated Successfully.',
            'statusCode' => 200,
        ]]);    
    }

    /*
        configurationDestroy function is deleting data. ServiceTrait where all Services are handled. In destroy function
    */ 
    public function configurationDestroy(Request $request, $id, $model){
        return $this->destroy($request, $id, $model);        
    }

    public function configurationGeneralSettingsEdit(Request $request, $id = null, $model){
        $data = $model::where('type', 'general_setting')->get();
        return success('configurations.updateGeneralSetting', ['data' => [                    
            'data' => $data,
            'status' => true,
            'message' => 'Configurations Fetched Successfully.',
            'statusCode' => 200,
        ]]); 
    }
    public function configurationSocialMediaEdit(Request $request, $id = null, $model){
        $data = $model::where('type', 'social_media')->get();
        return success('configurations.updateSocialMedia', ['data' => [                    
            'data' => $data,
            'status' => true,
            'message' => 'Configurations Fetched Successfully.',
            'statusCode' => 200,
        ]]); 
    }
    public function configurationEmailTemplatesEdit(Request $request, $id = null, $model){
        $data = $model::where('type', 'email_template')->get();
        return success('configurations.updateEmailTemplate', ['data' => [                    
            'data' => $data,
            'status' => true,
            'message' => 'Configurations Fetched Successfully.',
            'statusCode' => 200,
        ]]); 
    }

    public function configurationGeneralSettingsUpdate(Request $request, $id = null, $model){
        // Get all the request data
        $rules = [
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
            'favicon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
        ];
        validateRequest($request, $rules);
        $data = $request->except(
            ['_token']
        );
        if(!isset($data['coming_soon'])) {
            $data['coming_soon'] = 0;
        }
        if(!isset($data['status'])) {
            $data['status'] = 0;
        }
        // Loop through each item and save it
        foreach ($data as $name => $value) {
            $obj = getConfigObject($name);
            if($obj != null) {
                if($obj->input_type == 'text' || $obj->input_type == 'textarea' || $obj->input_type == 'number' || $obj->input_type == 'enum' || $obj->input_type == 'date') {

                    // Save or update the setting based on the 'name' column
                    $model::where('name', $name)->update([
                        'value' => $value
                    ]);
                }
                if($obj->input_type == 'checkbox') {
                    $model::where('name', $name)->update([
                        'value' => ($value == 'on') ? 1 : 0
                    ]);
                }
                if($obj->input_type == 'file') {
                    if ($request->hasFile($name)) {
                        $file = $request->file($name);
                        $filename = time() . '_' . $file->getClientOriginalName();
                        $path = $request->file($name)->storeAs('images', $filename, 'spaces');
                        $file_path = Storage::disk('spaces')->url('images/'.$filename);
                        if($file_path) {
                            $model::where('name', $name)->update([
                                'value' => $file_path
                            ]);                            
                        }
                    }
                }

            }
        }
        
        return redirect()->back()->with('success', 'General Settings Updated Successfully');
        
    }
    public function configurationSocialMediaUpdate(Request $request, $id = null, $model){
        // Get all the request data
        $data = $request->all();
        // Loop through each item and save it
        foreach ($data as $name => $value) {
            // Save or update the setting based on the 'name' column
            $model::where('name', $name)->update([
                'value' => $value
            ]);
        }

        return redirect()->back()->with('success', 'Social media settings updated successfully');
    }
    public function configurationEmailTemplatesUpdate(Request $request, $id = null, $model){
        // Get all the request data
        $data = $request->except(['_token']);
        
        // Loop through each item and save it
        foreach ($data as $name => $value) {
            // Save or update the setting based on the 'name' column
            $model::where('name', $name)->update([
                'value' => $value
            ]);
        }
        return redirect()->back()->with('success', 'Email templates setting updated successfully');
        
    }
}
