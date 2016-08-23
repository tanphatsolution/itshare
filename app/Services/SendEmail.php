<?php namespace App\Services;

use Illuminate\Support\Facades\Mail;
use App\Data\Blog\EmailLog;

class SendEmail
{
    public static function fire($data)
    {
        extract($data, EXTR_PREFIX_SAME, 'wddx');
        Mail::send($layout, $wddx_data, function ($message) use ($from, $to, $subject, $wddx_data) {
            $message->from($from['address'], $from['name']);
            $message->to($to['address'], $to['name'])->subject($subject);
            $emailLog = new EmailLog;
            $emailLog->type = empty($logType) ? 0 : $logType;
            $emailLog->mail_data = json_encode($wddx_data);
            $emailLog->receiver = json_encode($to);
            $emailLog->save();
        });
    }
}
