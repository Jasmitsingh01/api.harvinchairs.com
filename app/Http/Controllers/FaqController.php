<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Models\FaqType;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function getFaqType(){
        $faqtype = FaqType::where('status','active')->get();
        return response()->json(['data' => $faqtype]);
    }

    public function getGeneralFaq(Request $request){
        $faqs = Faq::whereNull('product_id')->where('status',true);
        //seach by faq title
        if(isset($request->search)){
            $faqs->where('question','like','%'.$request->search.'%');
        }
        if(isset($request->faq_type_id)){
            $faqs->where('faq_type_id',$request->faq_type_id);
        }
        $faqs = $faqs->get();
        return $faqs;
    }
}
