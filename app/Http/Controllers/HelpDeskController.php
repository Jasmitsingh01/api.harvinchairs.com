<?php

namespace App\Http\Controllers;

use App\Exceptions\MarvelException;
use App\Http\Requests\HelpDeskRequest;
use App\Jobs\SendMailJob;
use App\Mail\SendHelpDeskMail;
use App\Models\HelpDesk;
use Illuminate\Http\Request;

class HelpDeskController extends Controller
{
    public function sendmail(HelpDeskRequest $request){
        try{
            HelpDesk::create([
                'payload' => json_encode($request->all())
            ]);

            //send mail
            $tags = [
                'app_url' => env("APP_URL"),
                "app_name" => env("APP_NAME"),
                'name' => 'Administrator',
                "data" => json_encode($request->all()),
                "url" => config('app.url').'/shop'
            ];
            SendMailJob::dispatch(config('constants.HELPDESK_EMAIL_RECEIVER'),new SendHelpDeskMail($request->all()));
            return response()->json(['status' => true,'message' => 'success']);
        } catch(\Exception $e){
            dd($e->getMessage());
            throw new MarvelException(SOMETHING_WENT_WRONG);
        }
    }
}
