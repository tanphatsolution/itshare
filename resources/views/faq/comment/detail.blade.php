<div class="faq-comment-answer">
    <p class="comment-answer-info">Answer ({{ $answers->count() }})</p>
    @foreach($answers as $answer)
        <?php
        $helpful_btn = $answer->getHelpFullButton();
        ?>
        <div class="comment-answer-list">
            <div class="comment-answer-left">
                <span>{{ $answer->number_helpful }}</span>
                {{ $helpful_btn }}
            </div>
            <div class="comment-answer-right">
                <div class="main-answer">
                    @if($answer->best_answer)
                        <span class="noti-best-answer">Best Answer</span>
                    @endif
                    <div class="answer-user-top">
                        <a href="{{ route('getUser', $answer->user->username) }}" class="answer-user-avatar"><img
                                    src="{{ user_img_url($answer->user) }}"></a>
                        <a href="{{ route('getUser', $answer->user->username) }}" class="answer-user-name">
                            {{ $answer->user->name }}
                        </a>
                        <div class="answer-user-info">
                            <div class="answer-user-info-left">
                                <span class="line-right">{{ $answer->user->total_best_answer }} Best Answer</span>
                                <span>{{ $answer->user->total_helpful_answer }} Helpful</span>
                            </div>
                            <div class="answer-user-info-right">
                                <div class="answer-user-time">{{ $answer->created_at }}</div>

                            </div>
                            <div class="clr"></div>
                        </div>
                    </div>
                    <div class="answer-user-middle">
                        <p>
                            {{{ $answer->content }}}
                        </p>
                    </div>
                    @if($answer->best_answer)
                        <button class="remove-best">Remove from Best Answer</button>
                    @endif
                    <div class="clr"></div>
                    @if($answer->subQuestion)
                        @foreach($answer->subQuestion as $sub)
                            <?php
                            $helpful_btn = $sub->getHelpFullButton();
                            $user = App\Data\System\User::findOrFail($sub->user_id);
                            ?>
                            <div class="answer-user-bottom">
                                <div class="reply-answer-comment {{ $user->hightLight() }}">
                                    <div class="comment-answer-left">
                                        <span>{{ $sub->number_helpful }}</span>
                                        <button class="unhelpful">Unhelpful</button>
                                    </div>
                                    <div class="comment-answer-right">
                                        <div class="comment-answer-right-inner">
                                            @if($user->hightLight())
                                                <button class="highlight">
                                                    <img src="../img/ico-highlight.png">
                                                </button>
                                                <div class="highlight-popup">
                                                    <p>This user got 10 more best answer or 20 more helpful</p>
                                                    <img src="../img/ico-arrow2.png">
                                                </div>
                                            @endif
                                            <div class="answer-user-top">
                                                <a href="{{ route('getUser', $user->username) }}"
                                                   class="answer-user-avatar"><img
                                                            src="{{ user_img_url($user) }}"></a>
                                                <a href="{{ route('getUser', $user->username) }}"
                                                   class="answer-user-name">
                                                    {{ $user->name }}
                                                </a>
                                                <div class="answer-user-info">
                                                    <div class="answer-user-info-left">
                                                        <span class="line-right">{{ $user->total_best_answer }} Best Answer</span>
                                                        <span>{{ $user->total_helpful_answer }} Helpful</span>
                                                    </div>
                                                    <div class="answer-user-info-right">
                                                        <div class="answer-user-time">answered 22 hours ago
                                                        </div>
                                                        <button class="answer-user-edit"></button>
                                                    </div>
                                                    <div class="clr"></div>
                                                </div>
                                            </div>
                                            <div class="answer-user-middle">
                                                <p>
                                                    {{{ $sub->content }}}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <button class="btn-reply-comment">Add comment</button>
                                <div class="clr"></div>
                                <div class="reply-answer-comment-form">
                                    Comment...
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    @endforeach
</div>