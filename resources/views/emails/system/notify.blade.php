@extends('emails.layouts.default')

@section('main')
{{ isset($lang) ? App::setLocale($lang) : App::setLocale('en') }}
<h1 style="font-size:20px;color:rgb(40,40,40)">{{ Config::get('app.app_name') }}!</h1>
<div>
    <div style="padding:30px">
        <div class="alert alert-danger" role="alert">
            <div class="alert alert-danger" role="alert">
                {{ nl2br(trans('messages.notification.feedback_from.system_backup_mail_content')) }}
            </div>
        </div>
    </div>
</div>

@stop
