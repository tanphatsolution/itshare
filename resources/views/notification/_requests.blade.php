@foreach ($requests as $request)
<li class="entry-item break-word notify-{{ $request->id }}">
    <div class="entry-avatar" style="background: url({{ $request->sender->getAvatar(60) }})">
    </div>
    <div class="info-box">
        {{ \App\Services\HelperService::notifyRawMessage($request) }}
        <div class="viblo-btn-group clearfix">
            <a href="javascript:void(0)" data-request-id="{{ $request->id }}" class="accept request-accept">{{ trans('labels.accept') }}</a>
            <a href="javascript:void(0)" data-request-id="{{ $request->id }}" class="decline request-deny">{{ trans('labels.decline') }}</a>
        </div>
        <span class="entry-time">{{ $request->updated_at->diffForHumans() }}</span>
    </div>
</li>
@endforeach
