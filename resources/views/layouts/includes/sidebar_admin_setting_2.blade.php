<div class="left-admin col-md-2">
    <ul id="accordion" class="accordion">
        <li>
            <div class="link user">{{ trans('labels.sb_user_manager') }}<i class="fa fa-caret-down"></i></div>
            <ul class="submenu">
                <li class="active"><a href="{{ action('UsersController@getView') }}"><i class="fa fa-stop"></i>{{ trans('labels.sb_list') }}</a></li>
                <li><a href="{{ action('UsersController@getStatistic') }}"><i class="fa fa-stop"></i>{{ trans('labels.user.statistic') }}</a></li>
            </ul>
        </li>
        <li>
            <div class="link post">{{ trans('labels.sb_post_manager') }}<i class="fa fa-caret-down"></i></div>
            <ul class="submenu">
                <li><a href="{{ action('PostsController@getList') }}"><i class="fa fa-stop"></i>{{ trans('labels.post_list') }}</a></li>
                <li><a href="{{ action('PostsController@getStatistic') }}"><i class="fa fa-stop"></i>{{ trans('labels.post_statistic.statistic') }}</a></li>
            </ul>
        </li>
        <li>
            <div class="link language">{{ trans('labels.language_manager.label') }}<i class="fa fa-caret-down"></i></div>
            <ul class="submenu">
                <li class="active"><a href="{{ action('UserPostLanguagesController@getStatistic') }}"><i class="fa fa-stop"></i>{{ trans('labels.language_manager.statistic') }}</a></li>
            </ul>
        </li>
        <li>
            <div class="link home">{{ trans('labels.monthly_theme.top_page') }}<i class="fa fa-caret-down"></i></div>
            <ul class="submenu">
                <li><a href="{{ action('MonthlyThemeSubjectsController@create') }}"><i class="fa fa-stop"></i>{{ trans('labels.monthly_theme.create') }}</a></li>
                <li><a href="{{ action('MonthlyThemeSubjectsController@getView', [null, null]) }}"><i class="fa fa-stop"></i>{{ trans('labels.monthly_theme.back_number') }}</a></li>
            </ul>
        </li>
        <li>
            <div class="link a_category">{{ trans('labels.sb_categories_manager') }}<i class="fa fa-caret-down"></i></div>
            <ul class="submenu">
                <li><a href="{{ action('CategoriesController@create') }}"><i class="fa fa-stop"></i>{{ trans('labels.sb_create') }}</a></li>
                <li><a href="{{ action('CategoriesController@getView') }}"><i class="fa fa-stop"></i>{{ trans('labels.sb_edit') }}</a></li>
                <li><a href="{{ action('CategoryRequestsController@getView') }}"><i class="fa fa-stop"></i>{{ trans('labels.requests') }}</a></li>
                <li><a href="{{ action('CategoriesController@getImport') }}"><i class="fa fa-stop"></i>{{ trans('labels.import_category') }}</a></li>
                <li><a href="{{ action('CategoriesController@getExport') }}"><i class="fa fa-stop"></i>{{ trans('labels.export_category') }}</a></li>
            </ul>
        </li>
        <li>
            <div class="link report">{{ trans('labels.sb_feedback_manager') }}<i class="fa fa-caret-down"></i></div>
            <ul class="submenu">
                <li><a href="{{ action('FeedbacksController@index') }}"><i class="fa fa-stop"></i>{{ trans('labels.sb_list') }}</a></li>
            </ul>
        </li>
        <li>
            <div class="link report">{{ trans('labels.sb_report_manager') }}<i class="fa fa-caret-down"></i></div>
            <ul class="submenu">
                <li><a href="{{ action('ReportsController@index') }}"><i class="fa fa-stop"></i>{{ trans('labels.sb_list') }}</a></li>
            </ul>
        </li>
        <li>
            <div class="link role">{{ trans('labels.sb_role_manager') }}<i class="fa fa-caret-down"></i></div>
            <ul class="submenu">
                <li><a href="{{ action('RoleController@getPrivilege') }}"><i class="fa fa-stop"></i>{{ trans('labels.sb_privilege_control') }}</a></li>
                <li><a href="{{ action('RoleController@getChange') }}"><i class="fa fa-stop"></i>{{ trans('labels.sb_change_role') }}</a></li>
            </ul>
        </li>
        <li>
            <div class="link sever">{{ trans('labels.sb_server') }}<i class="fa fa-caret-down"></i></div>
            <ul class="submenu">
                <li><a href="{{ action('ServerController@getDeploy') }}"><i class="fa fa-stop"></i>{{ trans('labels.sb_deploy') }}</a></li>
            </ul>
        </li>
        <li>
            <div class="link sever">{{ trans('labels.import_file_csv') }}<i class="fa fa-caret-down"></i></div>
            <ul class="submenu">
                <li><a href="{{ action('AccountController@getCreate') }}"><i class="fa fa-stop"></i>{{ trans('labels.account') }}</a></li>
            </ul>
        </li>
        <li>
            <div class="link sever">{{ trans('labels.client.title_manage') }}<i class="fa fa-caret-down"></i></div>
            <ul class="submenu">
                <li><a href="{{ action('OAuthController@createApp') }}"><i class="fa fa-stop"></i>{{ trans('labels.client.title_create') }}</a></li>
                <li><a href="{{ action('OAuthController@getApps') }}"><i class="fa fa-stop"></i>{{ trans('labels.client.title_manage') }}</a></li>
            </ul>
        </li>
        <li>
            <div class="link sever">{{ trans('labels.contest.label') }}<i class="fa fa-caret-down"></i></div>
            <ul class="submenu">
                <li><a href="{{ action('ContestController@index') }}"><i class="fa fa-stop"></i>{{ trans('labels.contest.view') }}</a></li>
            </ul>
        </li>
    </ul>
    <div class="copyright">&copy;Framgia Inc</div>
</div>
