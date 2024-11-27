<?php

namespace App\Http\Controllers;

use App\Services\PageService;
use Illuminate\Http\Request;

class PageController extends Controller
{

    protected $page = null;

    public function __construct(PageService $pageService){
        $this->page = $pageService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $id = null, $model = 'App\Models\Page') {

        return $this->page->pageIndex($request, $id, $model);
    }

    public function create(Request $request)
    {
        // Only to View ROle Create.
    }

    public function store(Request $request, $id = null, $model = 'App\Models\Page')
    {        
        return $this->page->pageStore($request, $id, $model);
    }

    public function show(Request $request, $id = null, $model = 'App\Models\Page')
    {
        return $this->page->pageShow($request, $id, $model);
    }

    public function update(Request $request, $id = null, $model = 'App\Models\Page')
    {
       return $this->page->pageUpdate($request, $id, $model);
    }

    public function destroy(Request $request, $id = null, $model = 'App\Models\Page')
    {        
        return $this->page->pageDestroy($request, $id, $model);
    }
}
