{{ Form::open(['action' => 'MonthlyThemeSubjectsController@postUpdate', 'method' => 'POST', 'class' => 'form-horizontal', 'role' => 'form', 'files' => true, 'id' => 'monthly-theme-form']) }}
    <div class="control-group public">
        <input type="hidden" name="monthlyThemeSubjectId" value="{{ $monthlyThemeSubject['id'] }}">
        <label class="control-label col-lg-2">{{ trans('labels.monthly_theme.publish_month') }}</label>
        <input type="hidden" value="{{ $monthlyThemeSubject['publish_month'] }}" name="publish_month">
        {{ Form::select('publish_month', $timeOptions['months'], $monthlyThemeSubject['publish_month'], ['disabled']) }}
        <input type="hidden" value="{{ $monthlyThemeSubject['publish_year'] }}" name="publish_year">
        {{ Form::select('publish_year', $timeOptions['years'], $monthlyThemeSubject['publish_year'], ['disabled']) }}
    </div>
    <div class="control-group theme">
        <label class="control-label col-lg-2">{{ trans('labels.monthly_theme.theme_name') }}</label>
        {{ Form::text('subject_theme_name', $monthlyThemeSubject['theme_name'], ['placeholder' => trans('labels.monthly_theme.theme_name'), 'maxlength' => '100']) }}
    </div>
    <div class="control-group">
        <label class="control-label col-lg-2">{{ trans('labels.monthly_theme.images') }}</label>
        {{ Form::file('image', ['id' => 'theme-image', 'data-message' => trans('messages.image.invalid_file_size'), 'data-size' => Config::get('image')['max_image_size']]) }}
        <input type="hidden" name="userChangeMainPicture" value="0" id="user-change-main-picture" /> 
    </div>
    <div class="slideadmin">
        <label class="control-label col-lg-2"></label>
        <div class="col-lg-10 thumb-preview">
        @if (isset($monthlyThemeSubject['img']))
            <img class="container" src="/{{ $monthlyThemeSubject['img'] }}" alt="admin">
        @else
            <img class="container" src="/img/img-slideadmin.jpg" alt="admin">
        @endif
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-lg-2">{{ trans('labels.monthly_theme.display_slider') }}</label>
        <div class="col-md-8 no-padding-left">
            {{ Form::checkbox('display_slider', '1', $monthlyThemeSubject['display_slider'], ['class' => 'bootstrap-switch-input']) }}
        </div>
    </div>
    <div class="url-professionals">
        <div class="control-group">
            <label for="professional" class="control-label col-lg-2">{{ trans('labels.monthly_theme.professional') }}</label>
            <div class="col-lg-10 no-padding-left">
                <?php $count = count($monthlyProfessionals) ?>
                @if ($count == 0)
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
                @endif

                @foreach ($monthlyProfessionals as $index => $monthlyProfessional)
                    <div class="professional-element fader @if (isset($zeroOpacity)) {{ 'zeroOpacity' }} @endif">
                        <input type="hidden" value="{{ $monthlyProfessional['id'] }}" name="monthlyProfessionalId[{{ $index }}]">
                        {{ Form::text('url['. $index . ']', $monthlyProfessional['url'], ['class' => 'url']) }}
                        @if ($count != ($index + 1))
                            <button type="button" class="remove remove-professional" data-professional-id="{{ $monthlyProfessional['id'] }}"></button>
                        @else
                            <button class="add add-professional" type="button" data-professional-id="{{ $monthlyProfessional['id'] }}"></button>
                        @endif
                        <div class="clearfix"></div>
                        <div class="control-group">
                            <label class="control-label col-lg-2">{{ trans('labels.monthly_theme.professional_img') }}</label>
                            {{ Form::file('professional_imgs['. $index . ']', ['class' => 'professional-image', 'data-message' => trans('messages.image.invalid_file_size'), 'data-size' => Config::get('image')['max_image_size']]) }}
                            @if (!empty($monthlyProfessional['professional_img']))
                                <img class="professional-img" src="/{{ $monthlyProfessional['professional_img'] }}" />
                            @endif
                        </div>
                        <div class="control-group">
                            <label class="control-label col-lg-2">{{ trans('labels.monthly_theme.slider_img') }}</label>
                            {{ Form::file('slider_imgs['. $index . ']', ['class' => 'slider-image', 'data-message' => trans('messages.image.invalid_file_size'), 'data-size' => Config::get('image')['max_image_size']]) }}
                            @if (!empty($monthlyProfessional['slider_img']))
                                <img class="professional-img" src="/{{ $monthlyProfessional['slider_img'] }}" />
                            @endif
                        </div>
                    </div>
                @endforeach
                <div class="professionals"></div>
            </div>
        </div>
    </div>
    <div class="professional-removed-id"></div>
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
            <?php $countTheme = count($monthlyThemes) ?>
            <?php $count = 0 ?>
                @foreach ($monthlyThemes as $index => $monthlyTheme)
                <?php $count++ ?>
                <div class="theme-element fader @if (isset($zeroOpacity)) {{ 'zeroOpacity' }} @endif">
                    <input type="hidden" name="monthlyThemesId[]" value="{{ $index }}">
                    <div class="vietnam">
                        <input type="hidden" name="theme_id[vi][]" value="{{ $monthlyTheme['vi']['id'] }}" />
                        {{ Form::text('theme_name[vi][]', $monthlyTheme['vi']['name'], ['class' => 'theme', 'maxlength' => '100']) }}
                    </div>
                    <div class="eng">
                        <input type="hidden" name="theme_id[en][]" value="{{ $monthlyTheme['en']['id'] }}" />
                        {{ Form::text('theme_name[en][]', $monthlyTheme['en']['name'], ['class' => 'theme', 'maxlength' => '100']) }}
                    </div>
                    <div class="eng">
                        <input type="hidden" name="theme_id[ja][]"
                            value="{{ isset($monthlyTheme['ja']['id']) ? $monthlyTheme['ja']['id'] : null }}" />
                        {{ Form::text('theme_name[ja][]',
                            isset($monthlyTheme['ja']['name']) ? $monthlyTheme['ja']['name'] : null,
                            ['class' => 'theme', 'maxlength' => '100'])
                        }}
                    </div>
                    @if ($countTheme != $count)
                        <button class="remove remove-theme" type="button" data-theme-vi-id="{{ $monthlyTheme['vi']['id'] }}" data-theme-en-id="{{ $monthlyTheme['en']['id'] }}" data-monthly-theme-id="{{ $index }}"></button>
                    @else
                        <button class="add add-theme-this-month" type="button" data-theme-vi-id="{{ $monthlyTheme['vi']['id'] }}" data-theme-en-id="{{ $monthlyTheme['en']['id'] }}" data-monthly-theme-id="{{ $index }}"></button>
                    @endif
                    <div class="clearfix"></div>
                </div>
                @endforeach
                @if ($countTheme == 0)
                    <div class="theme-element fader @if (isset($zeroOpacity)) {{ 'zeroOpacity' }} @endif">
                        <div class="vietnam">
                            {{ Form::text('theme_name[vi][]', null, ['class' => 'theme']) }}
                        </div>
                        <div class="eng">
                            {{ Form::text('theme_name[en][]', null, ['class' => 'theme']) }}
                        </div>
                        <div class="jap">
                            {{ Form::text('theme_name[ja][]', null, ['class' => 'theme']) }}
                        </div>
                        <button class="add add-theme-this-month" type="button"></button>
                        <div class="clearfix"></div>
                    </div>
                @endif
                <div class="themes-this-month"></div>
            </div>
        </div>
    </div>
    <div class="theme-removed-id"></div>
    <div class="monthly-theme-removed-id"></div>
    <div class="control-group submit">
        <label class="control-label col-lg-2"></label>
        {{ Form::submit(trans('buttons.preview'), ['class' => 'preview', 'disabled']) }}
        <input class="save" type="button" value="{{ trans('buttons.save') }}" />
    </div>
{{ Form::close() }}
<script type="text/javascript">
    var aProfessional = {{ json_encode(View::make('monthlythemes._a_professional', ['zeroOpacity' => true])->render()) }};
    var maxProfessional = {{ Config::get('limitation')['theme_max_professionals'] }};
    var aTheme = {{ json_encode(View::make('monthlythemes._a_theme_in_month', ['zeroOpacity' => true])->render()) }};
    var maxTheme = {{ Config::get('limitation')['theme_max_themes'] }};
    var imgMaxSize = {{ Config::get('image')['max_image_size'] }};
</script>
{{ HTML::script('js/theme.js') }}
{{ HTML::script('js/theme-edit.js') }}