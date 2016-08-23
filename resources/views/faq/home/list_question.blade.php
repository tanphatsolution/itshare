@if(!empty($items))
    @foreach($items as $item)
        <li>
            <article class="entry-item">
                <div class="entry-left">
                    <span class="status {{ $item->solved == 0 ? 'Unsolved' : 'solved' }}">
                        {{ $item->solved == 0 ? Lang::get('labels.question.unsolved') : Lang::get('labels.question.solved') }}
                    </span>
                    <div class="answer-count">
                        <span class="number">{{ $item->number_answer }}</span>
                        <span>{{ Lang::get('labels.question.answers') }}</span>
                    </div>
                    <div class="entry-meta-mobile ">
                        <span class="answer-count-xs">{{ $item->number_answer }}</span>
                        <span class="clips-count-xs">{{ $item->number_clip }}</span>
                        <span class="views-count-xs">{{ $item->number_view }}</span>
                    </div>
                </div>

                <div class="entry-box">
                    <a href="{{ route('faq.questions.show',[ 'id'=> $item->id, 'slug'=>$item->slug]) }}"
                       class="entry-name">
                        {{{ $item->title }}}
                    </a>
                    <p class="description">
                        {{{ \App\Helper\Home::getQuestionDescription($item->content) }}}
                    </p>

                    <div class="row">
                        <div class="col-xs-12 col-sm-6">
                            <div class="entry-meta">
                                <span class="number"><b>{{ $item->number_clip }}</b> {{ Lang::get('labels.clip') }}</span>
                                <span class="number"><b>{{ $item->number_view }}</b> {{ Lang::get('labels.view') }}</span>
                            </div>
                            <div class="tags">
                                @foreach($item->categories as $category)
                                    <a href="#">{{{ $category->name }}}</a>
                                @endforeach
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6 t-r">
                            <span class="group-author">
                                <a href="{{{ url_to_user($item->user) }}}"
                                   class="author-link-to-profile">
                                    <img class="author" src="{{ user_img_url($item->user, 20) }}"
                                         alt="{{{ $item->user->name }}}">
                                    <span>{{{ $item->user->name }}}</span>
                                </a>
                            </span>
                            <span class="date-time">
                                 <span>
                                     {{ Lang::get('labels.question.questioned') }} {{ \App\Helper\Home::convertTime($item->published_at) }}
                                 </span>
                            </span>
                        </div>

                    </div>
                </div>
            </article>
        </li>
    @endforeach
@endif
