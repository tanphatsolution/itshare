@extends('emails.layouts.default')

@section('main')
{{ isset($lang) ? App::setLocale($lang) : App::setLocale('en') }}
<h1 style="font-size:20px;color:rgb(40,40,40)">{{ Config::get('app.app_name') }}!</h1>
<div>
    <div style="padding:30px">
        <div class="alert alert-success" role="alert">
            @if ($type == 'feedback')
                <div class="alert alert-success" role="alert">
                    {{ 
                        trans(
                            'messages.notification.admin_feedback',
                            [
                                'click_here' => link_to_action(
                                    'FeedbacksController@index',
                                    trans('messages.notification.click_here')
                                )
                            ]
                        ) 
                    }}
                </div>
            @else
                <div class="alert alert-success" role="alert">
                    {{ 
                        trans(
                            'messages.notification.admin_report',
                            [
                                'click_here' => link_to_action(
                                    'ReportsController@index',
                                    trans('messages.notification.click_here')
                                )
                            ]
                        ) 
                    }}
                </div>
            @endif
        </div>
    </div>
</div>

@stop
