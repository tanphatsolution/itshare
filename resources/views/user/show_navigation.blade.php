<div class="user-nav-wrapper row">
    <div>
        <ol class="breadcrumb">
            <li><a href="{{ URL::action('UsersController@getShow', $user->username) }}">{{{ $user->username }}}</a></li>
            <li class="active">{{ $userNavigation }}</li>
        </ol>
    </div>
</div>