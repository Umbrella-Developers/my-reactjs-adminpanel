<?php

namespace App\Http\Controllers;

use App\Services\ResourceService;
use Illuminate\Http\Request;

class ResourceController extends Controller
{
    protected $resource = null;

    public function __construct(ResourceService $resourceService){
        $this->resource = $resourceService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $id = null, $model = 'App\Models\Resource') {

        return $this->resource->resourceIndex($request, $id, $model);
    }

    public function create(Request $request)
    {
        // Only to View ROle Create.
    }

    public function store(Request $request, $id = null, $model = 'App\Models\Resource')
    {        
        return $this->resource->resourceStore($request, $id, $model);
    }

    public function show(Request $request, $id = null, $model = 'App\Models\Resource')
    {
        return $this->resource->resourceShow($request, $id, $model);
    }

    public function update(Request $request, $id = null, $model = 'App\Models\Resource')
    {
       return $this->resource->resourceUpdate($request, $id, $model);
    }

    public function destroy(Request $request, $id = null, $model = 'App\Models\Resource')
    {        
        return $this->resource->resourceDestroy($request, $id, $model);
    }
}
