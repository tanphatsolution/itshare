@foreach ($userStocks as $userStock)
    <li>
        <section class="itemsShowAuthorInfo clearfix">
            <div class="pull-left">
                <a href="{{ url_to_user($userStock) }}">
                    {{ user_img_tag($userStock, 40, 'userIcon') }}
                </a>
                <div class="profile">
                    <strong class="userName">
                        <a href="{{ url_to_user($userStock) }}">
                            {{ $userStock->name }}
                        </a>
                    </strong>
                    <div class="followers">
                        <span class="count number-follow">{{ $userStock->followers()->count()}}</span>
                        <span class="unit">{{ trans('labels.followers') }}</span>
                    </div>
                </div>
            </div>
                @if(isset($currentUser))
                    <div class="pull-right follow-action">
                        @include('relationships.relationships', ['currentUser' => $currentUser, 'user' => $userStock])
                    </div>
                @endif
        </section>
    </li>
@endforeach
