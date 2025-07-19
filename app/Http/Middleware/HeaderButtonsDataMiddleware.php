<?php

namespace App\Http\Middleware;

use App\Models\ContactUs;
use App\Models\CreativeCutsEnquire;
use App\Models\User;
use App\Models\EmailLog;
use Closure;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\ProductEnquire;

class HeaderButtonsDataMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */

public function handle($request, Closure $next)
{
    // Fetch the data for the header
    $orderNotification = Order::where(['notification'=>true,'parent_id'=>null])->get();
    $contactUsNotification =  ContactUs::where('notification',true)->get();
    $newCustomerNotification =  User::where('notification',true)->get();
    $creativeCutsEnquireNotification = CreativeCutsEnquire::where('notification',true)->get();
    $productEnquireNotification = ProductEnquire::where('notification',true)->get();
// dd($orderNotification, $contactUsNotification,$newCustomerNotification);
    // Share the data with all views
    view()->share(['orderNotification'=> $orderNotification ,'contactUsNotification'=> $contactUsNotification,'newCustomerNotification'=> $newCustomerNotification ,'creativeCutsEnquireNotification'=>$creativeCutsEnquireNotification ,'productEnquireNotification'=>$productEnquireNotification]);

    return $next($request);
}
}
