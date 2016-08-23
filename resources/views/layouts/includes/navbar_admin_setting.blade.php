<nav class="navbar navbar-default">
    <div class="container-fluid">
        @if (App\Facades\Authority::hasRole('admin'))
        <div class="navbar-header">
            <a class="navbar-brand" href="{{ action('SettingsController@getAdminIndex') }}">{{ trans('labels.admin') }}</a>
        </div>
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                        <i class="fa fa-child"></i>
                        {{ trans('labels.sb_user_manager') }}
                        <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu" role="menu">
                        <li>
                            <a href="{{ action('UsersController@getView') }}">
                                <i class="fa fa-caret-right"></i>
                                {{ trans('labels.sb_list') }}
                            </a>
                        </li>
                        <li>
                            <a href="{{ action('UsersController@getStatistic') }}">
                                <i class="fa fa-caret-right"></i>
                                {{ trans('labels.user.statistic') }}
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                        <i class="fa fa-bar-chart"></i>
                        {{ trans('labels.sb_post_manager') }}
                        <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu" role="menu">
                        <li>
                            <a href="{{ action('PostsController@getList') }}">
                                <i class="fa fa-caret-right"></i>
                                {{ trans('labels.post_list') }}
                            </a>
                        </li>
                        <li>
                            <a href="{{ action('PostsController@getStatistic') }}">
                                <i class="fa fa-caret-right"></i>
                                {{ trans('labels.post_statistic.statistic') }}
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                        <i class="fa fa-tags"></i>
                        {{ trans('labels.sb_categories_manager') }}
                        <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu" role="menu">
                        <li>
                            <a href="{{ action('CategoriesController@create') }}">
                                <i class="fa fa-plus"></i>
                                {{ trans('labels.sb_create') }}
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="{{ action('CategoriesController@getView') }}">
                                <i class="fa fa-edit"></i>
                                {{ trans('labels.sb_edit') }}
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="{{ action('CategoryRequestsController@getView') }}">
                                <i class="fa fa-exclamation-triangle"></i>
                                {{ trans('labels.requests') }}
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                        <i class="fa fa-archive"></i>
                        {{ trans('labels.sb_feedback_manager') }}
                        <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu" role="menu">
                        <li>
                            <a href="{{ action('FeedbacksController@index') }}">
                                <i class="fa fa-caret-right"></i>
                                {{ trans('labels.sb_list') }}
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                        <i class="fa fa-exclamation-triangle"></i>
                        {{ trans('labels.sb_report_manager') }}
                        <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu" role="menu">
                        <li>
                            <a href="{{ action('ReportsController@index') }}">
                                <i class="fa fa-caret-right"></i>
                                {{ trans('labels.sb_list') }}
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                        <i class="fa fa-users"></i>
                        {{ trans('labels.sb_role_manager') }}
                        <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu" role="menu">
                        <li>
                            <a href="{{ action('RoleController@getPrivilege') }}">
                                <i class="fa fa-caret-right"></i>
                                {{ trans('labels.sb_privilege_control') }}
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="{{ action('RoleController@getChange') }}">
                                <i class="fa fa-caret-right"></i>
                                {{ trans('labels.sb_change_role') }}
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                        <i class="fa fa-cloud"></i>
                        {{ trans('labels.sb_server') }}
                        <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu" role="menu">
                        <li>
                            <a href="{{ action('ServerController@getDeploy') }}">
                                <i class="fa fa-caret-right"></i>
                                {{ trans('labels.sb_deploy') }}
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                        <i class="fa fa-cloud"></i>
                        {{ trans('labels.monthly_theme.title') }}
                        <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu" role="menu">
                        <li>
                            <a href="{{ action('MonthlyThemeSubjectsController@create') }}">
                                <i class="fa fa-caret-right"></i>
                                {{ trans('labels.monthly_theme.create') }}
                            </a>
                        </li>
                        <li>
                            <a href="{{ action('MonthlyThemeSubjectsController@getView') }}">
                                <i class="fa fa-caret-right"></i>
                                {{ trans('labels.monthly_theme.back_number') }}
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
        @endif
    </div>
</nav>
<br>