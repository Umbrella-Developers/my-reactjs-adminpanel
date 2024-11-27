<?php

namespace App\Services;

use App\Models\Faq;
use App\Traits\ServiceTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
/**
 * Class FAQService.
 */
class FAQService
{
    /*
        Imported ServiceTrait File 
    */ 
    use ServiceTrait;

    /*
        faqIndex function is showing listed data. ServiceTrait where all Services are handled. In index function
    */ 
    public function faqIndex(Request $request, $id, $model){       
        return $this->index($request, $id, $model);
    }
    
    /*
        faqStore function is storing  data. ServiceTrait where all Services are handled. In store function
    */ 
    public function faqStore(Request $request, $id, $model){
        $requestArray = null;
        //Validated
        $rules = [
            'question' => 'required',
            'answer' => 'required',
            'section' => 'required',
        ];
    
        // Validate the request using the helper function
        validateRequest($request, $rules);    
        return $this->store($request, $id, $model, $requestArray);
    }

    public function faqEdit($id){
        $faq = Faq::find($id);     
        return success('faqs.index', ['data' => [                    
            'status' => true, 
            'message' => 'FAQ information fetched to edit.',
            'faq' => $faq,
            'statusCode' => 200,
        ]]);
    }

    /*
        faqShow function is showing single  data. ServiceTrait where all Services are handled. In show function
    */ 
    public function faqShow(Request $request, $id, $model){
        if(empty($request)){
            return $this->show($request, $id, $model, $search = true);
        }
        return $this->show($request, $id, $model, $search = false);    
    }

    /*
        faqDestroy function is deleting data. ServiceTrait where all Services are handled. In destroy function
    */ 
    public function faqDestroy(Request $request, $id, $model){
        return $this->destroy($request, $id, $model);        
    }

    /*
        faqUpdate function is update data. ServiceTrait where all Services are handled. In update function
    */ 
    public function faqUpdate(Request $request, $id, $model){
            $requestArray = null;
            //Validated
            $rules = [
                'question' => 'required',
                'answer' => 'required',
                'section' => 'required',
            ];
        
            // Validate the request using the helper function
            validateRequest($request, $rules);          
            return $this->update($request, $id, $model, $requestArray);       
    }
}
