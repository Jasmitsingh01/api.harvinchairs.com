<?php

namespace App\Traits;

// Event for notification email, alert, sms
use App\Events\SendMail;
use App\Models\EmailLog;
use Validator;
use Indapoint\EmailTemplate\app\Models\EmailTemplate;
use Indapoint\AdminConfiguration\Configuration;
use Exception;
use Illuminate\Support\Facades\Log;

/**
 * Email trait
 */
trait EmailTrait
{

    public function sendEmailNotification($template, $toIds = [], $tags = [])
    {
       try{
            // get template
            $email_template = EmailTemplate::getTemplate(['template_code' => $template]);
            if($email_template){
                //$contents=file_get_contents(resource_path().'/'.config('constants.email_templates_path').$email_template->email_file_name);
            $content = view($email_template->email_file_name,$tags)->render();

            if(!empty($toIds)){
                    foreach ($toIds as $id) {
                        $toEmail =  $id;
                        $toUserFullname = $tags['name'];
                        //$toUserFullname = $tags['name'];
                        $fromName = $tags['FROM_NAME'] = config('mail.from.name');
                        $fromEmail = config('mail.from.address');
                        $search = $replace = [];
                        foreach ($tags as $k => $v) {
                            array_push($search, "[{$k}]");
                            array_push($replace, $v);
                        }
                        $mailData['toName'] = $toUserFullname; //$toUserFullname
                        $mailData['toEmail'] = $toEmail; //$toEmail
                        $mailData['fromName'] = $fromName;//$fromName
                        $mailData['fromEmail'] = $fromEmail;
                        $mailData['emailSubject'] = nl2br(str_replace($search, $replace, $email_template->subject));
                        //$mailData['emailContent'] = str_replace($search, $replace, $contents);
                        $mailData['emailContent'] = $content;
                       // dd($tags);
                        $mailData['attachment'] = (isset($tags['attachment'])) ? $tags['attachment'] : '';
                        //attachment
                        $data = \Event::dispatch(new SendMail($mailData));
                        $emailData = [
                            'recipient' => $mailData['toEmail'],
                            'template_name' => $template,
                            'subject' => $mailData['emailSubject'],
                            'status' => 'success',
                            'orderid' => isset($tags['order']) ? $tags['order']->id : null,
                        ];
                        EmailLog::create($emailData);

                        //dd($data);
                        return true;
                    }
                }
            }
            return false;
        } catch(\Exception $e){
            //dd($e->getMessage());
            Log::info('mail exception'.$e->getMessage());
        }
    }
}
