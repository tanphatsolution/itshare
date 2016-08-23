<li class="language-element">
    {{ Form::select('languages[]', \Config::get('detect_language.code'), isset($language) ? $language : 1, ['class' => 'form-control language-post']) }}
    <button class="btn btn-sm btn-default remove-language" data-type="selectedLanguages" type="button">{{ trans('buttons.remove') }}</button>
</li>
