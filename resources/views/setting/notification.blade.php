@extends('layouts.default')

@section('css')

{{ HTML::style(version('css_min/setting_language.min.css')) }}

@stop

@section('main')

<div class="col-md-12 setting">
    @include('layouts.includes.sidebar_setting')

    <div class="role col-md-9 col-sm-8">
        <div class="panel panel-info">
            <div class="panel-heading">
                <div class="panel-title">{{ trans('messages.notification.title') }}</div>
            </div>
            <div class="panel-body" >
                {{ Form::open(['action' => 'SettingsController@postNotification', 'class' => 'form-horizontal', 'role' => 'form']) }}

                @include('elements.message_notify', ['errors' => $errors])

                @foreach (App\Data\Blog\Setting::getNotificationSettingFields() as $notifiField)
                    <div class="form-group">
                        {{ Form::label($notifiField, trans('messages.setting.privacy_'.App\Services\HelperService::snakeCaseNoSpace(App\Services\SettingService::getFieldNotificationLabel($notifiField))), ['class' => 'col-md-4 control-label']) }}
                        <div class="col-md-8">
                            {{ Form::checkbox($notifiField, '1', $notifiSetting->$notifiField, ['class' => 'bootstrap-switch-input']) }}
                        </div>
                    </div>
                @endforeach
                <div class="pull-right">
                    {{ Form::submit(trans('buttons.change'), ['class' => 'btn btn-success']) }}
                    <a class="btn btn-primary" href="{{ URL::action('SettingsController@getNotification') }}">{{ trans('buttons.cancel') }}</a>
                </div>
            {{ Form::close() }}
            </div>
        </div>
    </div>

</div>

@stop


@section('script')
    <script>
        var textShow = '{{ trans('labels.show') }}';
        var textHide = '{{ trans('labels.hide') }}';
        var textYes = '{{ trans('labels.yes') }}';
        var textNo = '{{ trans('labels.no') }}';
    </script>
    {{ HTML::script(version('js_min/setting_language.min.js')) }}
@stop
