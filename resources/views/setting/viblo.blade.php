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
                <div class="panel-title">{{ trans('messages.setting.sb_viblo_setting') }}</div>
            </div>
            <div class="panel-body" >
                {{ Form::open(['action' => 'SettingsController@postViblo', 'class' => 'form-horizontal', 'role' => 'form']) }}

                @include('elements.message_notify', ['errors' => $errors])

                <div class="form-group">
                    {{ Form::label('theme', trans('messages.setting.user_theme'), ['class' => 'col-md-4 control-label']) }}
                    <div class="col-md-3">
                        {{ Form::select('viblo_theme', \App\Data\Blog\Setting::getThemeSettingFields(), $setting->viblo_theme, ['class' => 'form-control', 'id' => 'themeSelect']); }}
                    </div>
                </div>
                <div class="pull-right">
                    {{ Form::submit(trans('buttons.change'), ['class' => 'btn btn-success']) }}
                    <a class="btn btn-primary" href="{{ URL::action('SettingsController@getIndex') }}">{{ trans('buttons.cancel') }}</a>
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
