<?php

namespace App\Http\Controllers;

use App\Facades\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebHookController extends CoreController
{

    public function stripe(Request $request)
    {
        return Payment::handleWebHooks($request);
    }

    public function paypal(Request $request)
    {
        Log::debug($request);
        return Payment::handleWebHooks($request);
    }

    public function razorpay(Request $request)
    {
        return Payment::handleWebHooks($request);
    }
}
