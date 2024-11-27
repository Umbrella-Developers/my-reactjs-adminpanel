<?php

namespace App\Http\Controllers;

use App\Services\FAQService;
use Illuminate\Http\Request;

class FAQController extends Controller
{
    protected $faq = null;

    public function __construct(FAQService $faqService){
        $this->faq = $faqService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $id = null, $model = 'App\Models\Faq') {

        return $this->faq->faqIndex($request, $id, $model);
    }

    public function create(Request $request)
    {
        // Only to View ROle Create.
    }

    public function edit($id)
    {
        return $this->faq->faqEdit($id);
    }

    public function store(Request $request, $id = null, $model = 'App\Models\Faq')
    {        
        return $this->faq->faqStore($request, $id, $model);
    }

    public function show(Request $request, $id = null, $model = 'App\Models\Faq')
    {
        return $this->faq->faqShow($request, $id, $model);
    }

    public function update(Request $request, $id = null, $model = 'App\Models\Faq')
    {
       return $this->faq->faqUpdate($request, $id, $model);
    }

    public function destroy(Request $request, $id = null, $model = 'App\Models\Faq')
    {        
        return $this->faq->faqDestroy($request, $id, $model);
    }
}
