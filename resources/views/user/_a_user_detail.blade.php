@if (!empty($user))
    <div class="col-md-6 col-lg-6 row category">
        <a class="ctg-logo col-md-4 col-lg-4" href="{{ url_to_user($user) }}" title="{{ $user->name }}"><img src="{{ asset('img/blank.png') }}" style="background: url({{ user_img_url($user, 300) }})"></a>
        <div class="ctg-description col-md-8 col-lg-8">
            <a class="name" href="{{ url_to_user($user) }}" title="{{{ $user->name }}}">{{{ $user->name }}}</a>
            <div class="nummber-statistic">
              <a class="number-post" href="{{ url_to_user($user) }}">{{ $user->publishedPosts()->count() }} {{ trans('labels.posts') }}</a>
              <a href="{{ URL::action('UsersController@getFollowers', $user->username) }}"><span class="number-follow">{{ $user->followers()->count() }}</span> &nbsp;{{ trans('labels.followers') }}</a>
              <div class="clear-both"></div>
            </div>
            <p class="text-content">{{ HTML::entities($user->profile->description) }}</p>
            @include('relationships.relationships', ['currentUser' => $currentUser, 'user' => $user, 'class' => 'btn-follow-mini'])
        </div>
    </div>
@endif