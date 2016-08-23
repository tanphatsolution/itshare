<div class="wrap-notify-tabs">
    <!-- Nav tabs -->
    <div class="notify-tabs-header clearfix">
        <ul class="nav nav-tabs notify-tabs" role="tablist">
            <li role="presentation" class="active">
                <a href="{{ isset($flag) && $flag ? '#notifications-desktop-res' : '#notifications-desktop' }}"
                    aria-controls="{{ isset($flag) && $flag ? 'notifications-desktop-res' : 'notifications-desktop' }}"
                    role="tab" data-toggle="tab">
                    {{ trans('labels.notifications') }}
                    <span class="notify-count notifications">0</span>
                </a>
            </li>
            <li role="presentation">
                <a href="{{ isset($flag) && $flag ? '#request-desktop-res' : '#request-desktop' }}"
                    aria-controls="{{ isset($flag) && $flag ? 'request-desktop-res' : 'request-desktop' }}"
                    role="tab" data-toggle="tab">
                    {{ trans('labels.requests') }}
                    <span class="notify-count requests">0</span>
                </a>
            </li>
        </ul>
        <a class="notify-setting" href="{{ action('SettingsController@getNotification') }}">
            <img src="{{ asset('/img/icon-setting5.png') }}" alt="setting">
        </a>
    </div>
    <!-- /.notify-tabs-header -->

    <div class="clearfix"></div>
    <!-- Tab panes -->
    <div class="tab-content notify-content">
        <div role="tabpanel" class="tab-pane active" id="{{ isset($flag) && $flag ? 'notifications-desktop-res' : 'notifications-desktop' }}">
            <ul class="list-post" id="notification-list">
                <li id="notify-loading" class='notify-loading entry-item hide'>
                </li>
            </ul>
            <a href="{{ action('NotificationsController@index') }}" class="see-all">{{ trans('labels.see_all') }}</a>
        </div>
        <!-- /#notifications -->
        <div role="tabpanel" class="tab-pane" id="{{ isset($flag) && $flag ? 'request-desktop-res' : 'request-desktop' }}">
            <ul class="list-post" id="requests-list">
                <li id="notify-loading" class='notify-loading entry-item hide'>
                </li>
            </ul>
            <a href="{{ action('NotificationsController@index') }}" class="see-all">{{ trans('labels.see_all') }}</a>
        </div>
        <!-- /#request -->
    </div>
</div>
<!-- /.wrap-tabs -->
