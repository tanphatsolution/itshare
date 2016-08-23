@if(!empty($latest_question))
    <div class="module latest-question gray-box">
        <h4 class="module-title">{{ Lang::get('labels.question.latest_questions') }}</h4>
        <div class="module-content">
            <ul class="list-items">
                @foreach($latest_question as $question)
                    <li class="item">
                        <div class="meta">
                            <span class="status {{ $question->solved == 1 ? 'solved' : 'unsolved' }}">
                                {{ $question->solved == 1 ? Lang::get('labels.question.solved') : Lang::get('labels.question.unsolved') }}
                            </span>
                            <span class="count-clip">{{ $question->number_clip . ' ' . Lang::get('labels.clip') }}</span>
                            <span class="count-answer">{{ $question->number_answer . ' ' . Lang::get('labels.question.answers') }}</span>
                        </div>
                        <a href="{{ route('faq.questions.show',['id'=>$question->id,'slug'=>$question->slug ]) }}">
                            {{{ $question->title }}}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
@endif

