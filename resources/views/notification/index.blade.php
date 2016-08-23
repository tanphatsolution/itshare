@extends('layouts.default')

@section('css')
    {{ HTML::style(version('css_min/notification_index.min.css')) }}
@stop

@section('main')

<div class="list-notify">
    <div class="container">
        <p class="your-notify">{{ trans('messages.notification.your_notifications')}}</p>
        @foreach ($notifications as $notification)
            <div class="time-notify">
            <p>
                @if (date('Ymd', strtotime($notification['date'])) == date('Ymd'))
                    {{ trans('messages.notification.today')}}
                @elseif (date('Ymd', strtotime($notification['date'])) == date('Ymd', strtotime('-1 days')))
                    {{ trans('messages.notification.yesterday')}}
                @else
                    {{ date('M d', strtotime($notification['date'])) }}
                @endif
            </p>
            <div class="box-notify">
                @foreach ($notification['notifications'] as $noti)
                    <div class="alert break-word" role="alert">
                        @if ($noti->sender_id != 0)
                            <a href="{{ url_to_user($noti->sender) }}">
                                <img class="avar" src="{{ asset('img/blank.png') }}"
                                    style="background: url({{ user_img_url($noti->sender, 50) }})">
                            </a>
                        @else
                            <a href="javascript:void(0)">
                                <img class="avar" src="{{ asset('img/blank.png') }}"
                                    style="background: url(http://www.gravatar.com/avatar/00000000000000000000000000000000?d=mm&f=y)">
                            </a>
                        @endif
                        {{ \App\Services\HelperService::notifyMessage($noti->type, $noti) }}
                    </div>
                @endforeach
            </div>
            </div>
        @endforeach
    </div>
    {{ $paginate->render() }}
</div>

@stop
