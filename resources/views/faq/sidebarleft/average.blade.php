<div class="module response-time2">
    <div class="response-time2-left">
        <span class="title">Averaged after</span>
        <span class="time">1h 30m</span>
    </div>
    <div class="response-time2-right">
        <span class="title">Solved after</span>
        <span class="time">1h 30m</span>
    </div>
    <div class="clr"></div>
</div>

<div class="post-author">
    <a href="{{ $qAuthor['url'] }}" class="author-avatar">
        <img src="{{ $qAuthor['avatar'] }}">
    </a>
    <div class="box-info-author">
        <a class="author-name" href="{{ $qAuthor['url'] }}">{{{ $qAuthor['name'] }}}</a>
        <a class="author-username" href="{{ $qAuthor['url'] }}">@ {{{ $qAuthor['username'] }}}</a>
        <div class="btn-post-follow">
            <a class="btn-post-r" href="{{ $qAuthor['url'] }}">
                {{ $qAuthor['publishedQuestion'] }}
            </a>
            <a class="btn-follow-r" href="{{ $qAuthor['getUserFollowers'] }}">
                {{ $qAuthor['followerUser'] }}
            </a>
            <div class="clear-both"></div>
        </div>

        @if (isset($currentUser))
            <div class="btn-follow-author">
                @include('relationships.relationships', [
                 'user' => $qAuthor
                ])
            </div>
        @endif
    </div>
</div>