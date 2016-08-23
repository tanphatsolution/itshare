@if(!empty($users))
<div>
    <input placeholder="Search">
    <ul class="faq-request-answer-list">
        @foreach($users as $user)
        <li class="faq-request-answer-user">
            <a href="{{ route('getUser', $user['username']) }}" class="answer-user-avatar"><img
                        src="{{ $user['avatar'] }}"></a>
            <a href="{{ route('getUser', $user['username']) }}" class="answer-user-name">
                {{{ $user['name'] }}}
            </a>
            <span class="line-right">{{ $user['best_answer'] . trans('labels.question.best_answer') }}</span>
            <span>{{ $user['helpful'] . trans('labels.question.best_answer') }}</span>
            <button class = "{{ $user['request_class'] }}">Request an answer</button>
            <div class="clr"></div>
        </li>
        @endforeach
        <button class="faq-request-answer-loadmore">{{ trans('labels.load_more') }}</button>
    </ul>
</div>
@endif