<?php

namespace App\Http\Controllers;

use App\Models\Configuration;
use App\Services\ConfigurationService;
use Illuminate\Http\Request;

class ConfigurationController extends Controller
{

    protected $configuration = null;

    public function __construct(ConfigurationService $configurations){
        $this->configuration = $configurations;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $id = null, $model = '\App\Models\Configuration')
    {
        $generalSetting = Configuration::where('type', 'general_setting')->get();
        $socialMedia = Configuration::where('type', 'social_media')->get();
        $emailTemplate = Configuration::where('type', 'email_template')->get();
        return view('configurations.index', [
            'generalSetting' => $generalSetting,
            'socialMedia' => $socialMedia,
            'emailTemplate' => $emailTemplate,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return $this->configuration->configurationStore($request);
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function show(Request $request, $id = null, $model = 'App\Models\Configuration')
    {
        return $this->configuration->configurationShow($request, $id, $model);
    }

    public function edit($id)
    {
        return $this->configuration->configurationEdit($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        return $this->configuration->configurationUpdate($request, $id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id = null, $model = 'App\Models\Configuration')
    {
        return $this->configuration->configurationDestroy($request, $id, $model);
    }

    
    public function generalSettingsEdit(Request $request, $id = null, $model = 'App\Models\Configuration')
    {
        return $this->configuration->configurationGeneralSettingsEdit($request, $id, $model);
    }

    public function socialMediaEdit(Request $request, $id = null, $model = 'App\Models\Configuration')
    {
        return $this->configuration->configurationSocialMediaEdit($request, $id, $model);
    }

    public function emailTemplatesEdit(Request $request, $id = null, $model = 'App\Models\Configuration')
    {
        return $this->configuration->configurationEmailTemplatesEdit($request, $id, $model);
    }
    

    //
    public function generalSettingsUpdate(Request $request, $id = null, $model = 'App\Models\Configuration')
    {
        return $this->configuration->configurationGeneralSettingsUpdate($request, $id, $model);
    }

    public function socialMediaUpdate(Request $request, $id = null, $model = 'App\Models\Configuration')
    {
        return $this->configuration->configurationSocialMediaUpdate($request, $id, $model);
    }

    public function emailTemplatesUpdate(Request $request, $id = null, $model = 'App\Models\Configuration')
    {
        return $this->configuration->configurationEmailTemplatesUpdate($request, $id, $model);
    }
}
