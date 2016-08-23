<nav class="header-res">
    <div class="container">
        <div class="header-res-logo">
            <a class="global-logo pull-left" href="{{ action('HomeController@getTopPage') }}"
                title="{{ Config::get('app.app_name') }}" >
                <img src="{{ asset('img/logo-top-page.png') }}" alt="logo">
            </a>
        </div>
        <div class="header-res-menu">
            <ul class="controls pull-right">
                @if (\App\Facades\Authority::check() && isset($currentUser))
                    <li class="mobile-noti" role="button" aria-controls="m3"
                        aria-expanded="false" tabindex="0">
                        <img src="{{ asset('/img/btn-bell2.png') }}">
                        <div id="notification-badge-count-res" data-init="true"
                            class="notify @if (isset($notificationsCount) && !$notificationsCount) {{ 'hide' }} @endif">
                            {{ $notificationsCount }}
                        </div>
                    </li>
                    <li class="mobile-avatar" role="button" aria-controls="m2"
                        aria-expanded="false" tabindex="0">
                        <img src="{{ user_img_url($currentUser, 50) }}">
                    </li>
                @else
                    <li class="mobile-avatar" role="button" aria-controls="m2"
                        aria-expanded="false" tabindex="0">
                        <img src="{{ asset('img/btn-header-avar.png') }}">
                    </li>
                @endif
                <li class="mobile-menu" role="button" aria-controls="m1"
                    aria-expanded="false" tabindex="0">
                    <img src="{{ asset('/img/icon-menu2.png') }}" alt="menu">
                </li>
            </ul>
        </div>
    </div>
</nav>

<div id="region_wrapper">
    <div class="container">
        <div id="m1" class="message" tabindex="-1" role="region" aria-labelledby="m1-label" aria-hidden="true">
            <ul>
                <li>
                    {{ Form::open(['url' => URL::to('/search'), 'role' => 'search',
                        'id' => 'header-search-form', 'class' => 'search-container']) }}
                        <input type="text" class="search-box"
                            placeholder="{{ trans('labels.search') }}" name="keyword">
                    {{ Form::close() }}
                </li>
                @if (\App\Facades\Authority::check() && isset($currentUser))
                    <li>
                        {{ link_to_action('PostsController@getIndex', trans('labels.stock'),
                            ['wall' => \App\Services\PostService::WALL_STOCK], ['class' => 'link-clip']) }}
                    </li>
                @endif
                <li class="dropdown">
                    @if (!empty($userJoinGroups))
                        <a href="#" class="link-group">
                            <span>{{ trans('labels.group') }}</span>
                        </a>
                        <?php $groups = App\Services\GroupService::limit($currentUser->groups()) ?>
                        <ul>
                            @if(isset($groups) && count($groups)))
                                @foreach ($groups as $group)
                                    <li>
                                        <a href="{{ url_to_group($group) }}">
                                            <div class="group-img pull-left">
                                                <img class="border-radius-50-percent" src="{{ group_img_link($group, 'profile') }}">
                                            </div>
                                            <div class="group-name">
                                                <h4>{{ $group->name }}</h4>
                                            </div>
                                        </a>
                                    </li>
                                @endforeach
                            @endif
                            <a href="{{ URL::to('groups') }}" class="group-see-all">{{ trans('labels.see_all') }}</a>
                        </ul>
                    @else
                        <a href="{{ URL::to('groups') }}" class="link-group">
                        <span>{{ trans('labels.group') }}</span>
                        </a>
                    @endif
                </li>
                @if (\App\Facades\Authority::check() && isset($currentUser))
                    <li>
                        <a href="{{ URL::to('posts/create') }}" class="link-post">
                            {{ trans('labels.write_post') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ action('PostsController@draft', ['encryptedId' => null]) }}" class="link-draft">
                            {{ trans('labels.draft') }}
                        </a>
                    </li>
                @endif
            </ul>
        </div>
        <div id="m2" class="message" role="region" aria-labelledby="m2-label" tabindex="-1" aria-hidden="true">
            <ul>
                @if (\App\Facades\Authority::check() && isset($currentUser))
                    <li>
                        <a href="{{ url_to_user($currentUser) }}" class="link-profile">
                            {{ trans('labels.profile') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ action('SettingsController@getIndex') }}" class="link-setting">
                            {{ trans('labels.setting') }}
                        </a>
                    </li>
                @endif
                <li>
                    <a href="javascript:void(0)" id="language-setting-res" class="link-language language-setting">
                        {{ trans('labels.language') }}
                    </a>
                </li>
                @if (\App\Facades\Authority::check() && isset($currentUser))
                    <li>
                        <a href="{{ action('UsersController@getLogout') }}" class="link-logout">
                            {{ trans('labels.logout') }}
                        </a>
                    </li>
                @else
                    <li>
                        <a href="{{ action('UsersController@getLogin') }}" class="link-logout" id="viblo-login"
                            data-toggle="modal" data-target="#viblo-login-dialog">
                            {{ trans('labels.login') }}
                        </a>
                    </li>
                @endif
            </ul>
        </div>
        <div id="m3" class="message" role="region" aria-labelledby="m3-label" tabindex="-1" aria-hidden="true">
            <div id="notification-content-res" class="notify-down">
                @include('notification._popup_notification', ['flag' => true])
            </div>
        </div>
    </div>
</div>

<nav class="navbar">
    <div class="container header admin-header">
        <div class="left col-lg-7">
            <a class="global-logo" href="{{ action('HomeController@getTopPage') }}" title="{{ Config::get('app.app_name') }}">
                <img src="{{ asset('img/logo-v2.png') }}" alt="logo">
            </a>
            <div class="box-search">
                {{ Form::open(['url' => URL::to('/search'), 'role' => 'search', 'id' => 'header-search-form', 'class' => 'search-container']) }}
                    <input type="text" id="search-box" class="search-box"
                        placeholder="{{ trans('labels.search') }}" name="keyword">
                    <label for="search-box">
                        <span><img class="search-icon" src="{{ asset('img/btn-header-search.png') }}"></span>
                    </label>
                {{ Form::close() }}
            </div>
            <ul class="menu-list">
                @if (App\Facades\Authority::check())
                    <li>
                        {{ link_to_action('PostsController@getIndex', trans('labels.stock'),
                            ['wall' => App\Services\PostService::WALL_STOCK], ['class' => App\Services\PostService::WALL_STOCK]) }}
                    </li>
                @endif
                <li class="dropdown">
                    <a href="{{ URL::to('groups') }}" class="navbar-menu-groups">
                        {{ trans('labels.group') }} <span class="caret"></span>
                    </a>
                    @if (!empty($userJoinGroups))
                        <ul class="dropdown-menu" role="menu">
                            <?php $groups = App\Services\GroupService::limit($currentUser->groups()) ?>
                            <div id="target">
                                @foreach ($groups as $group)
                                <li>
                                    <div class="navbar-menu-name-groups">
                                        <a href="{{ url_to_group($group) }}">
                                            <img class="border-radius-50-percent" src="{{ group_img_link($group, 'profile') }}">
                                            {{ $group->name }}
                                        </a>
                                    </div>
                                </li>
                                @endforeach
                            </div>
                            <li class="see-all"><a href="{{ URL::to('groups') }}">{{ trans('labels.see_all') }}</a></li>
                        </ul>
                    @endif
                </li>
            </ul>
        </div>
        <div class="right">
            @if ($currentMonthThemeSubject)
                <a href="{{ url_to_themes($currentMonthThemeSubject->short_name) }}" >
                    <div class="name-theme">
                        <span>
                            @if ($lang == 'ja')
                                {{ trans('datetime.month.' . $currentMonthThemeSubject->publish_month) }}{{ trans('labels.monthly_theme.theme_of') }}: {{ $currentMonthThemeSubject->theme_name }}
                            @else
                                {{ trans('labels.monthly_theme.theme_of') }} {{ trans('datetime.month.' . $currentMonthThemeSubject->publish_month) }}: {{ $currentMonthThemeSubject->theme_name }}
                            @endif
                        </span>
                    </div>
                </a>
            @endif
            <ul class="user-link">
                @if (\App\Facades\Authority::check() && isset($currentUser))
                    <li class="post">
                        <a href="{{ URL::to('posts/create') }}">
                            {{ trans('labels.write_post') }}
                        </a>
                    </li>
                    <li class="drafts">
                        <a href="{{ action('PostsController@draft', ['encryptedId' => null]) }}">
                            {{ trans('labels.draft') }}
                        </a>
                    </li>
                @endif
            </ul>
            @if (\App\Facades\Authority::check() && isset($currentUser))
                <div id="notification-badge" class="user-login btn-notify" data-init="true">
                    <div class="inner">
                        <img src="{{ asset('/img/btn-bell.png') }}">
                        <div id="notification-badge-count"
                            class="notify @if (isset($notificationsCount) && !$notificationsCount) {{ 'hide' }} @endif">
                            {{ $notificationsCount }}
                        </div>
                    </div>
                    <div id="notification-content" class="notify-down">
                        @include('notification._popup_notification')
                    </div>
                </div>
                <div id="header-user" class="user-login">
                    <div class="user-avartar" style="background: url({{ user_img_url($currentUser, 50) }})"></div>
                    <img src="{{ asset('/img/btn-header-down.png') }}">
                    <div class="user-dropdown">
                        <ul>
                            <li>
                                <span class="glyphicon glyphicon-lock" aria-hidden="true"></span>
                                <a href="{{ url_to_user($currentUser) }}">
                                    {{ trans('labels.profile') }}
                                </a>
                            </li>
                            <li>
                                <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
                                <a href="{{ action('SettingsController@getIndex') }}">
                                    {{ trans('labels.setting') }}
                                </a>
                            </li>
                            <li>
                                <span class="glyphicon glyphicon-flag" aria-hidden="true"></span>
                                    <a href="javascript:void(0)" id="language-setting">
                                        {{ trans('labels.language') }}
                                    </a>
                            </li>

                            @if (App\Facades\Authority::hasRole('admin'))
                                <li>
                                    <span class="glyphicon glyphicon-user" aria-hidden="true"></span>
                                    <a href="{{ action('SettingsController@getAdminIndex') }}">
                                        {{ trans('labels.admin') }}
                                    </a>
                                </li>
                            @endif
                            <li>
                                <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
                                <a href="{{ action('UsersController@getLogout') }}">
                                    {{ trans('labels.logout') }}
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            @else
                <ul class="user-login">
                    <li>
                        <a href="{{ action('UsersController@getLogin') }}" id="viblo-login"
                            data-toggle="modal" data-target="#viblo-login-dialog">
                            {{ trans('labels.login') }}
                        </a>
                    </li>
                </ul>
            @endif
        </div>
    </div>
</nav>

<div class="clearfix"></div>

<div id="modal-language-settings-content"></div>

<script type="text/javascript">
    var isSetDefaultLang = {{ $isSetDefaultLang }};
    var title = "{{ isset($title) ? $title : Config::get('app.app_name') }}";
</script>
