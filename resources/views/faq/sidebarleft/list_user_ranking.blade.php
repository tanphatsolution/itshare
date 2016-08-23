@if(isset($users_ranking))
    @foreach($users_ranking as $key => $user)
        <li>
            <div class="user-ranking-info">
                <span class="ranking-number">{{ ++$key }}</span>
                <a href="{{ url_to_user($user) }}">
                    <span class="thumb">
                        <img class="author"
                             src="{{ \App\Services\UserRankingService::getAvatar($user) }}" alt="">
                    </span>
                    <span class="ovh">
                    <span class="user-name">{{{ $user->name }}}</span>
                    <span>{{ $user->sum_best_answer }} {{ Lang::get('labels.question.best_answer') }}</span>
                    </span>
                </a>
            </div>
            @if (isset($currentUser))
                <div class="btn-follow-author">
                    @include('relationships.relationships', [
                        'currentUser' => $currentUser,
                        'user' => json_decode(json_encode($user), true)
                    ])
                </div>
            @endif
        </li>
    @endforeach
@endif