@foreach ($languages as $language)
    @if ($language == App\Data\Blog\UserPostLanguage::SETTING_ALL_LANGUAGES)
        <div class="alert-information">{{ trans('messages.all_languages_selected') }}</div>
    @else
        @include('setting._a_language', ['language' => $language])
    @endif
@endforeach