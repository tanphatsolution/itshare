@if (isset($comment->user->username) && $comment->user->username != null)
    <div class="body-comment" id="comment{{ $comment->id }}">
        <div class="row">
            <a class="ava-bloger col-lg-1" href="{{ url_to_user($comment->user) }}" style="background: url({{ user_img_url($comment->user, 50) }})"></a>
            <div class="content-comment col-lg-11">
                <p class="name-bloger-c">
                    <a href="{{{ url_to_user($comment->user) }}}" class="author-link-to-profile">
                        <span>{{{ get_full_name_of_user($comment->user) }}}</span>
                    </a>
                    <span class="email">({{ '@' . $comment->user->username }})</span>
                    <span class="date-time-comment">
                        {{ convert_to_japanese_date($comment->createdAt, $lang, trans('datetime.format.datetime')) }}
                    </span>
                </p>
                <p class="contribution">
                    {{ App\Services\UserService::getContribution($comment->user) }}
                    {{ \App\Services\HelperService::myPluralizer(
                        trans('buttons.comment.contribution')
                        , \App\Services\UserService::getContribution($comment->user)
                        , $lang) }}
                </p>
                @if(isset($currentUser))
                    <div class="btn-group">
                        @if(\App\Services\UserService::canEditComment($currentUser, $comment))
                            <a class="btn-edit edit-comment fake-link"></a>
                        @endif
                        @if(\App\Services\UserService::canDeleteComment($currentUser, $comment))
                            <a class="btn-delete delete-comment fake-link"
                                data-message="{{ trans('messages.comment.delete_confirm_message') }}"
                                data-title="{{ trans('messages.comment.delete_confirm_title') }}"
                                data-confirm="{{ trans('messages.comment.delete_confirm_confirm') }}">
                            </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>
        <div class="text-comment comment-show">
            <div class="display-comment break-word">{{ $comment->getParsedContent() }}</div>
        </div>
    </div>
@endif