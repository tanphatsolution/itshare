<div class="col-md-3 col-sm-4" id="accordion">
    <div class="list-group">
        <a href="{{ action('ProfilesController@getUpdate') }}" class="list-group-item {{ App\Services\HelperService::sidebarActive('ProfilesController@getUpdate') }}">
            <i class="fa fa-user"></i> {{ trans('labels.sb_public_profile') }}
        </a>
        <a href="{{ action('UserSkillsController@create') }}" class="list-group-item {{ App\Services\HelperService::sidebarActive('UserSkillsController@create') }}">
            <i class="fa fa-briefcase"></i> {{ trans('labels.sb_skill_settings') }}
        </a>
        <a href="{{ action('UsersController@getChangePassword') }}" class="list-group-item {{ App\Services\HelperService::sidebarActive('UsersController@getChangePassword') }}">
            <i class="fa fa-key"></i> {{ trans('labels.sb_change_password') }}
        </a>
        <a href="{{ action('SettingsController@getViblo') }}" class="list-group-item {{ App\Services\HelperService::sidebarActive('SettingsController@getViblo') }}">
            <i class="fa fa-cog"></i> {{ trans('messages.sb_viblo_setting.title') }}
        </a>
        <a href="{{ action('UserPostLanguagesController@getPostLanguages') }}" class="list-group-item {{ App\Services\HelperService::sidebarActive('UserPostLanguagesController@getPostLanguages') }}">
            <i class="glyphicon glyphicon-flag"></i> {{ trans('labels.language_setting') }}
        </a>
        <a href="{{ action('SettingsController@getNotification') }}" class="list-group-item {{ App\Services\HelperService::sidebarActive('SettingsController@getNotification') }}">
            <i class="fa fa-bell-o"></i> {{ trans('messages.notification.title') }}
        </a>
        <a href="{{ action('ImageController@getView') }}" class="list-group-item {{ App\Services\HelperService::sidebarActive('ImageController@getView') }}">
            <i class="fa fa-image"></i> {{ trans('labels.sb_manage_images') }}
        </a>
    </div>
</div>