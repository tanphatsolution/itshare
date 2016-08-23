<div class="col-md-3 col-sm-4" id="accordion">
    <div class="list-group">
        @if (App\FacadesAuthority::hasRole('admin'))
        <a class="list-group-item" data-toggle="collapse" data-parent="#accordion"
            href="#collapseUser" aria-expanded="false" aria-controls="collapseUser">
            <i class="fa fa-child"></i> {{ trans('labels.sb_user_manager') }}
        </a>
        <div id="collapseUser" class="panel-collapse collapse in">
            <a href="{{ action('UsersController@getView') }}" class="list-group-item mgl-15 {{ HelperService::sidebarActive('UsersController@getView') }}">
                <i class="fa fa-caret-right"></i> {{ trans('labels.sb_list') }}
            </a>
        </div>
        <a class="list-group-item" data-toggle="collapse" data-parent="#accordion"
            href="#collapseUser" aria-expanded="false" aria-controls="collapseUser">
            <i class="fa fa-child"></i> {{ trans('labels.sb_post_manager') }}
        </a>
        <div id="collapseUser" class="panel-collapse collapse in">
            <a href="{{ action('PostsController@getList') }}" class="list-group-item mgl-15 {{ HelperService::sidebarActive('PostsController@getList') }}">
                <i class="fa fa-caret-right"></i> {{ trans('labels.post_list') }}
            </a>
        </div>
        <a class="list-group-item" data-toggle="collapse" data-parent="#accordion"
            href="#collapseCategory" aria-expanded="false" aria-controls="collapseCategory">
            <i class="fa fa-tags"></i> {{ trans('labels.sb_categories_manager') }}
        </a>
        <div id="collapseCategory" class="panel-collapse collapse in">
            <a href="{{ action('CategoriesController@create') }}" class="list-group-item mgl-15 {{ HelperService::sidebarActive('CategoriesController@create') }}">
                <i class="fa fa-plus"></i> {{ trans('labels.sb_create') }}
            </a>
            <a href="{{ action('CategoriesController@getView') }}" class="list-group-item mgl-15 {{ HelperService::sidebarActive('CategoriesController@getView') }}">
                <i class="fa fa-edit"></i> {{ trans('labels.sb_edit') }}
            </a>
        </div>
        <a class="list-group-item" data-toggle="collapse" data-parent="#accordion"
            href="#collapseFeedback" aria-expanded="false" aria-controls="collapseFeedback">
            <i class="fa fa-archive"></i> {{ trans('labels.sb_feedback_manager') }}
        </a>
        <div id="collapseFeedback" class="panel-collapse collapse in">
            <a href="{{ action('FeedbacksController@index') }}" class="list-group-item mgl-15 {{ HelperService::sidebarActive('FeedbacksController@index') }}">
                <i class="fa fa-caret-right"></i> {{ trans('labels.sb_list') }}
            </a>
        </div>
        <a class="list-group-item" data-toggle="collapse" data-parent="#accordion"
            href="#collapseReport" aria-expanded="false" aria-controls="collapseReport">
            <i class="fa fa-exclamation-triangle"></i> {{ trans('labels.sb_report_manager') }}
        </a>
        <div id="collapseReport" class="panel-collapse collapse in">
            <a href="{{ action('ReportsController@index') }}" class="list-group-item mgl-15 {{ HelperService::sidebarActive('ReportsController@index') }}">
                <i class="fa fa-caret-right"></i> {{ trans('labels.sb_list') }}
            </a>
        </div>
        <a class="list-group-item" data-toggle="collapse" data-parent="#accordion"
            href="#collapseRole" aria-expanded="false" aria-controls="collapseRole">
            <i class="fa fa-users"></i> {{ trans('labels.sb_role_manager') }}
        </a>
        <div id="collapseRole" class="panel-collase collapse in">
            <a href="{{ action('RoleController@getPrivilege') }}" class="list-group-item mgl-15 {{ HelperService::sidebarActive('RoleController@getPrivilege') }}">
                <i class="fa fa-caret-right"></i> {{ trans('labels.sb_privilege_control') }}
            </a>
            <a href="{{ action('RoleController@getChange') }}" class="list-group-item mgl-15 {{ HelperService::sidebarActive('RoleController@getChange') }}">
                <i class="fa fa-caret-right"></i> {{ trans('labels.sb_change_role') }}
            </a>
        </div>
        <a class="list-group-item" data-toggle="collapse" data-parent="#accordion"
            href="#collapseServer" aria-expanded="false" aria-controls="collapseServer">
            <i class="fa fa-cloud"></i> {{ trans('labels.sb_server') }}
        </a>
        <div id="collapseServer" class="panel-collase collapse in">
            <a href="{{ action('ServerController@getDeploy') }}" class="list-group-item mgl-15 {{ HelperService::sidebarActive('ServerController@getDeploy') }}">
                <i class="fa fa-caret-right"></i> {{ trans('labels.sb_deploy') }}
            </a>
        </div>
        @endif
    </div>
</div>