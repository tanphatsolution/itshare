@extends('layouts.default')

@section('css')
    {{ HTML::style(version('css_min/setting_language.min.css')) }}
@stop

@section('main')
    <div class="col-md-12 setting">
        @include('layouts.includes.sidebar_setting')

        <div class="role col-md-9 col-sm-8">
            @include('elements.message_notify', ['errors' => $errors])

            <div class="panel panel-info">
                <div class="panel-heading">
                    <div class="panel-title">{{ trans('messages.setting.post_language') }}</div>
                </div>
                <div class="panel-body" >
                    {{ Form::open(['action' => 'UserPostLanguagesController@postLanguages', 'class' => 'form-horizontal', 'role' => 'form']) }}
                        <div class="form-inline">
                            <div class="clearfix"></div>
                            <div class="skills">
                                @include('setting._list_languages', ['languages' => $languages])
                            </div>
                        </div>
                        <br/>
                        <div class="pull-right">
                            <button type="button" class="btn btn-info inline-table add-language" id="add-language">
                                <i class="fa fa-plus"> {{ trans('labels.add_language') }}</i>
                            </button>
                            {{ Form::submit(trans('buttons.chose_selected'), ['class' => 'btn btn-success']) }}
                            {{ Form::submit(trans('buttons.chose_all'), ['class' => 'btn btn-warning', 'name' => 'allLanguages']) }}
                        </div>
                    {{ Form::close() }}
                </div>
            </div>

            <div class="panel panel-info">
                <div class="panel-heading">
                    <div class="panel-title">{{ trans('messages.setting.system_language') }}</div>
                </div>
                <div class="panel-body" >
                    {{ Form::open(['action' => 'UserPostLanguagesController@postLanguages', 'class' => 'form-horizontal', 'role' => 'form']) }}
                        <div class="form-group">
                            <div class="col-md-3">
                                {{ Form::select('lang', App\Services\LanguageService::getSystemLangOptions(), $setting->lang, ['class' => 'form-control', 'id' => 'system-language']) }}
                            </div>
                        </div>
                        <div class="pull-right">
                            {{ Form::submit(trans('buttons.change'), ['class' => 'btn btn-success', 'name' => 'changeSysLang']) }}
                        </div>
                    {{ Form::close() }}
                </div>
            </div>

            <div class="panel panel-info">
                <div class="panel-heading">
                    <div class="panel-title">{{ trans('messages.setting.default_post_language') }}</div>
                </div>
                <div class="panel-body" >
                    {{ Form::open(['action' => 'UserPostLanguagesController@postLanguages', 'class' => 'form-horizontal', 'role' => 'form']) }}
                    <div class="form-group">
                        <div class="col-md-3">
                            {{ Form::select('default_post_language', Config::get('detect_language')['code'], $setting->default_post_language, ['class' => 'form-control', 'id' => 'system-language']); }}
                        </div>
                    </div>
                    <div class="pull-right">
                        {{ Form::submit(trans('buttons.change'), ['class' => 'btn btn-success', 'name' => 'changeDefaultPostLang']) }}
                        {{ Form::submit(trans('buttons.apply_to_all_posts'), ['class' => 'btn btn-warning', 'name' => 'changeLanguageAllPosts']) }}
                    </div>
                {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
@stop

@section('script')
    <script>
        var aLanguage = {{ json_encode(\View::make('setting._a_language', ['language' => null, 'zeroOpacity' => true])->render()) }};
        var maxLanguages = {{ count(\Config::get('detect_language.code')) }};
    </script>
    {{ HTML::script(version('js_min/setting_language.min.js')) }}
@stop
