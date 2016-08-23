@foreach ($notifications as $notification)
    <li class="entry-item break-word">
        <a href="{{ \App\Services\HelperService::notifyLink($notification) }}">
            @if ($notification->sender_id != 0)
                <div class="entry-avatar" style="background: url({{ $notification->sender->getAvatar(60) }})">
                </div>
            @else
                <div class="entry-avatar"
                     style="background: url(http://www.gravatar.com/avatar/00000000000000000000000000000000?d=mm&f=y)">
                </div>
            @endif
            <div class="info-box">
                {{ \App\Services\HelperService::notifyRawMessage($notification) }}
                <span class="entry-time" title="{{ trans('messages.notification.notify_time') }}">
                {{ $notification->created_at->diffForHumans() }}
            </span>
            </div>
        </a>
    </li>
@endforeach
