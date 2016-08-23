@extends('layouts.admin')

@section('css')

{{ HTML::style(version('css_min/monthlythemes_create.min.css')) }}

@stop

@section('main')
<div class="container-fluid">
    <div class="admin">
    @include('layouts.includes.sidebar_admin_setting_2')

        <div class="right-admin col-lg-10">
            @if ($errors->has())
                <div class='alert alert-danger'>
                    @foreach($errors->all() as $message)
                        <p>{{ $message }}</p>
                    @endforeach
                </div>
            @endif
            <input class="creat" type="submit" value="{{ trans('labels.monthly_theme.create') }}">
            {{ Form::open(['action' => 'MonthlyThemeSubjectsController@store', 'method' => 'POST', 'class' => 'form-horizontal', 'role' => 'form', 'files' => true, 'id' => 'monthly-theme-form']) }}
                <div class="control-group">
                    <label class="control-label col-lg-2">{{ trans('labels.monthly_theme.publish_month') }}</label>
                    {{ Form::select('publish_month', $timeOptions['months'], isset($input['publish_month']) ? $input['publish_month'] : null, ['onChange' => 'checkCreatedTheme()', 'id' => 'publish-month']) }}
                    {{ Form::select('publish_year', $timeOptions['years'], isset($input['publish_year']) ? $input['publish_year'] : null, ['onChange' => 'checkCreatedTheme()', 'id' => 'publish-year']) }}
                </div>
                <div class="content-create">
                    <div class="control-group theme">
                        <label class="control-label col-lg-2">{{ trans('labels.monthly_theme.theme_name') }}</label>
                        {{Form::text('subject_theme_name', isset($input['subject_theme_name']) ? $input['subject_theme_name'] : null, ['placeholder' => trans('labels.monthly_theme.theme_name'), 'maxlength' => '100']) }}
                    </div>
                    <div class="control-group">
                        <label class="control-label col-lg-2">{{ trans('labels.monthly_theme.images') }}</label>
                        {{ Form::file('image', ['id' => 'theme-image', 'data-message' => trans('messages.image.invalid_file_size'), 'data-size' => Config::get('image')['max_image_size']]) }}
                        <input type="hidden" name="imgInput" value="{{ isset($input['imgInput']) ? $input['imgInput'] : '' }}" />
                    </div>
                    <div class="slideadmin">
                        <label class="control-label col-lg-2"></label>
                        <div class="col-lg-10 thumb-preview">
                            <img src="{{ !empty($input['imgInput']) ? '/' . $input['imgInput'] : '/img/img-slideadmin.jpg' }}" alt="admin">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-lg-2">{{ trans('labels.monthly_theme.display_slider') }}</label>
                        <div class="col-md-8 no-padding-left">
                            {{ Form::checkbox('display_slider', '1', isset($input['display_slider']) ? $input['display_slider'] : '1', ['class' => 'bootstrap-switch-input']) }}
                        </div>
                    </div>
                    <div class="url-professionals">
                        <div class="control-group">
                            <label for="professional" class="control-label col-lg-2">{{ trans('labels.monthly_theme.professional') }}</label>
                            <div class="col-lg-10 no-padding-left">
                                <div class="professional-element fader @if (isset($zeroOpacity)) {{ 'zeroOpacity' }} @endif">
                                    {{ Form::text('url[]', null, ['class' => 'url', 'placeholder' => 'Url']) }}
                                    <button class="add add-professional" type="button"></button>
                                    <div class="clearfix"></div>
                                    <div class="control-group">
                                        <label class="control-label col-lg-2">{{ trans('labels.monthly_theme.professional_img') }}</label>
                                        {{ Form::file('professional_imgs[]', ['class' => 'professional-image', 'data-message' => trans('messages.image.invalid_file_size'), 'data-size' => Config::get('image')['max_image_size']]) }}
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label col-lg-2">{{ trans('labels.monthly_theme.slider_img') }}</label>
                                        {{ Form::file('slider_imgs[]', ['class' => 'slider-image', 'data-message' => trans('messages.image.invalid_file_size'), 'data-size' => Config::get('image')['max_image_size']]) }}
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="professionals"></div>
                            </div>
                        </div>
                    </div>
                    <div class="all-themes">
                        <div class="control-group">
                            <label class="control-label langue col-lg-2">{{ trans('labels.monthly_theme.theme_this_month') }}</label>
                            <div class="vietnam">
                                <a href="#">{{ Config::get('detect_language.code')['vi'] }}</a>
                            </div>
                            <div class="eng">
                                <a href="#">{{ Config::get('detect_language.code')['en'] }}</a>
                            </div>
                            <div class="jap">
                                <a href="#">{{ Config::get('detect_language.code')['ja'] }}</a>
                            </div>
                            <div class="col-lg-10 no-padding-left">
                                <div class="theme-element fader @if (isset($zeroOpacity)) {{ 'zeroOpacity' }} @endif">
                                    <div class="vietnam">
                                        {{ Form::text('theme_name[vi][]', null, ['class' => 'theme', 'placeholder' => trans('labels.monthly_theme.theme'), 'maxlength' => '100']) }}
                                    </div>
                                    <div class="eng">
                                        {{ Form::text('theme_name[en][]', null, ['class' => 'theme', 'placeholder' => trans('labels.monthly_theme.theme'), 'maxlength' => '100']) }}
                                    </div>
                                    <div class="eng">
                                        {{ Form::text('theme_name[ja][]', null, ['class' => 'theme', 'placeholder' => trans('labels.monthly_theme.theme'), 'maxlength' => '100']) }}
                                    </div>
                                    <button class="add add-theme-this-month" type="button"></button>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="themes-this-month"></div>
                            </div>
                        </div>
                    </div>
                    <div class="control-group submit">
                        <label class="control-label col-lg-2"></label>
                        {{ Form::submit(trans('buttons.preview'), ['class' => 'preview', 'disabled']) }}
                        <input class="save" type="button" value="{{ trans('buttons.save') }}" />
                    </div>
                <div class="alert-created">
                    <div class="alert alert-warning">{{ trans('messages.theme.created_warning') }}</div>
                </div>
            {{ Form::close() }}
        </div>
        <div class="clearfix"></div>
    </div>
</div>

@stop

@section('script')

<script type="text/javascript">
    var aProfessional = {{ json_encode(View::make('monthlythemes._a_professional', ['zeroOpacity' => true])->render()) }};
    var maxProfessional = {{ Config::get('limitation')['theme_max_professionals'] }};
    var aTheme = {{ json_encode(View::make('monthlythemes._a_theme_in_month', ['zeroOpacity' => true])->render()) }};
    var maxTheme = {{ Config::get('limitation')['theme_max_themes'] }};
    var imgMaxSize = {{ Config::get('image')['max_image_size'] }};
</script>
    {{ HTML::script(version('js_min/monthlythemes_create.min.js')) }}
@stop
