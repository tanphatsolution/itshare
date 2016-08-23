<h5>
    <a class="follow-label" href="{{ URL::action('UsersController@getFollowing', $user->username) }}">{{ trans('labels.following_users') }} <span>{{ $user->following()->count() }}</span></a>
</h5>
{{--*/ $following = $user->following()->take(6)->get() /*--}}
<ul class="list-inline user-icon-list">
@foreach($following as $followed)
    <li>
        <a href="{{ URL::action('UsersController@getShow', ['username' => $followed->followed->username]) }}">{{$followed->followed->getAvatar(25, 'mm', 'g', true)}}</a>
    </li>
@endforeach
</ul>
