<h5>
    <a class="follow-label" href="{{ URL::action('UsersController@getFollowers', $user->username) }}">{{ trans('labels.followers') }} <span>{{$user->followers()->count()}}</span></a>
</h5>
{{--*/ $followers = $user->followers()->take(6)->get() /*--}}
<ul class="list-inline user-icon-list">
@foreach($followers as $follower)
    <li>
        <a href="{{ URL::action('UsersController@getShow', ['username' => $follower->follower->username]) }}">{{$follower->follower->getAvatar(25, 'mm', 'g', true)}}</a>
    </li>
@endforeach
</ul>
