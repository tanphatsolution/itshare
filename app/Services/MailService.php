<?php namespace App\Services;

class MailService
{
    const QUEUE_MAILS = 'emails';
    const EMAIL_LOG_TYPE_FEEDBACK = 1;
    const EMAIL_LOG_TYPE_CONFIRM = 2;
    const EMAIL_LOG_TYPE_PASSWORD_CHANGE = 3;
    const EMAIL_LOG_TYPE_REPORT = 4;

    public static function send($from, $to, $subject, $data, $layout, $logType = null)
    {
        $dataCompact = json_encode(compact('from', 'to', 'subject', 'data', 'layout'));
        $dataCompact = json_encode($dataCompact);
        $path = base_path();
        $stringCommand = 'php ' . $path . '/artisan viblo:send-mail '. $dataCompact;
        $command = $stringCommand;
        $command .= '> /dev/null 2>/dev/null &';
        exec($command);
    }
}
