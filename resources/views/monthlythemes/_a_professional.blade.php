<div class="professional-element fader @if (isset($zeroOpacity)) {{ 'zeroOpacity' }} @endif">
    {{ Form::text('url[]', isset($monthlyProfessional) ? $monthlyProfessional['url'] : null, ['class' => 'url', 'placeholder' => 'Url']) }}
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