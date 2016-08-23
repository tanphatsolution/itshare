<div class="container">
    <div class="box-title box-title-child">
        <ul class="list-title">
            <li><a class="showPosts" href="{{ URL::action('UsersController@getShow', $user->username) }}">{{ trans('labels.posts') }}</a></li>
            <li><a class="showStock" href="{{ URL::action('UsersController@getStock', $user->username) }}">{{ trans('labels.stock') }}</a></li>
            @if (isset($currentUser) && \App\Services\UserService::checkCurrentUser($currentUser, $user->username))
                <li><a class="showDraft" href="{{ URL::action('UsersController@getDraft', $user->username) }}">{{ trans('labels.draft') }}</a></li>
            @endif
            <li><a class="showFollowers" href="{{ URL::action('UsersController@getFollowers', $user->username) }}">{{ trans('labels.followers') }}</a></li>
            <li><a class="showFollowing" href="{{ URL::action('UsersController@getFollowing', $user->username) }}">{{ trans('labels.following_users') }}</a></li>
            <li><a class="showCategories" href="{{ URL::action('UsersController@getFollowingCategories', $user->username) }}">{{ trans('labels.following_categories') }}</a></li>
            <!-- <li><a class="showGroups" href="#">group</a></li> -->
        </ul>
    </div>
</div>