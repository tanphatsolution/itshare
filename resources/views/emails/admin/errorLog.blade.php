@extends('emails.layouts.default')

@section('main')
{{ isset($lang) ? App::setLocale($lang) : App::setLocale('en') }}
<h1 style="font-size:20px;color:rgb(40,40,40)">{{ trans('messages.send_mail.title', ['appName' => Config::get('app.app_name')]) }}</h1>
<div>{{ trans('messages.send_mail.header', ['appName' => Config::get('app.app_name')]) }}<br>
</div>
<div>
    <div style="padding:30px">
        {{  $data }}
    </div>
</div>

@stop