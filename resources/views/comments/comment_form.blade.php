<div class="row helpful-question">
    @if ($post->warningNotHelpful())
        <p class="helful-detail">
            <span class="label label-warning">{{ trans('messages.vote.not_helpful_warning') }}</span>
        </p>
    @endif
    <p class="helful-detail">
        <span>{{ trans('messages.vote.question') }}</span>
        <a type="button" id="btn-helpful"
           class="btn btn-xs helpful-button {{ $post->isVotedBy($currentUser) && !$post->isVotedHelpfulBy($currentUser) ? 'btn-voted' : '' }}"
           data-helpful="{{ \App\Data\Blog\PostHelpful::HELPFUL_YES }}">
            {{ trans('messages.vote.yes') }} ({{ $helpful['helpful_yes'] }})
        </a>
        <a type="button" id="btn-not-helpful"
           class="btn btn-xs helpful-button {{ $post->isVotedBy($currentUser) && $post->isVotedHelpfulBy($currentUser) ? 'btn-voted' : '' }}"
           data-helpful="{{ \App\Data\Blog\PostHelpful::HELPFUL_NO }}">
            {{ trans('messages.vote.no') }} ({{ $helpful['helpful_no'] }})
        </a>
    </p>
    <p class="voted">
        @if ($post->isVotedBy($currentUser))
            @if ($post->isVotedHelpfulBy($currentUser))
                <span class="label label-info">{{ trans('messages.vote.voted_helpful') }}</span>
            @else
                <span class="label label-info">{{ trans('messages.vote.voted_not_helpful') }}</span>
            @endif
        @endif
    </p>
</div>
<div class="box-comment">
    <div class="write-comment">
        <div class="row">
            <div class="col-md-4">
                <p>{{ trans('labels.new_comment') }}</p>
            </div>
            <div class="col-md-3 pull-right text-right">
                <div data-toggle="modal" data-target="#myModal" class="fake-link markdown-help">
                    <img src="{{asset('/img/markdown-icon1.png')}}"  alt="markdown"
                         class="markdown-icon"> {{ trans('labels.markdown.help') }}
                </div>
            </div>
        </div>
        @if (isset($currentUser))
            @include('comments._new_comment')
        @else
            <div class="text-warning text-center">
                {{
                    trans(
                        'comments.notify_login',
                        [
                            'link' => link_to_action(
                                'UsersController@getLogin',
                                trans('words.here'),
                                ['return' => URL::current()],
                                ['data-toggle' => 'modal', 'data-target' => '#viblo-login-dialog']
                            )
                        ]
                    )
                }}
            </div>
        @endif
    </div>
    <div class="list-comment">
        @include('comments._list_comments', ['comments' => $post->getComments(\App\Services\CommentService::COMMENT_DISPLAY_PER_PAGE, 0)])
        <div class="last-comment"></div>
    </div>
    @if ($post->comments->count() > \App\Services\CommentService::COMMENT_DISPLAY_PER_PAGE)
        {{ Form::button(trans('feedbacks.show.more'), ['class' => 'btn-more btn-detail', 'name' => 'more']) }}
    @endif
</div>

<script>
    var helpful_count = {{ $helpful['helpful_yes'] }};
    var not_helpful_count = {{ $helpful['helpful_no'] }};
</script>