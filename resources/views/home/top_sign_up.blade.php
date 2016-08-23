@extends('layouts.toppage')

@section('main')

<div id="popup-content">
    <div class="inner-content">
        <div class="content-top">{{ trans('labels.login') }}</div>
        <div class="content-body">
            <div class="content-body-top">
                <p>{{ trans('labels.description_popup_1') }}</p>
                <p>{{ trans('labels.other_signup') }}</p>
                <div class="socials">
                    <a href="{{ action('SocialsController@getFacebook') }}" class="ic-face"></a>
                    <a href="{{ action('SocialsController@getGoogle') }}" class="ic-google"></a>
                    <a href="{{ action('SocialsController@getGithub') }}" class="ic-git"></a>
                </div>
                <div class="clr"></div>
            </div>
            <div class="line-or">{{ trans('labels.or') }}</div>
            <div class="content-body-bottom form-content">
                <div class="message-error msg-error"></div>
                {{ Form::open(['action' => 'UsersController@postLogin', 'class' => 'viblo-login-form', 'role' => 'form']) }}
                    {{ Form::hidden('return', (URL::current() == URL::to('/')) ? action('HomeController@getTopPage') : URL::current()) }}
                    {{ Form::text('username', null,
                        ['id' => 'txtUsername', 'placeholder' => trans('labels.username') . ' ' . trans('labels.or') . ' ' . trans('labels.email')])
                    }}
                    <div class="username-error error"></div>
                    {{ Form::password('password', ['class' => 'input-last', 'placeholder' => trans('labels.password')]) }}
                    <div class="password-error error"></div>
                    <div class="cb-remember">
                        {{ Form::checkbox('remember', 1, true) }} {{ trans('labels.remember_me') }}
                    </div>
                    <br>
                    <div class="clr"></div>
                    {{ HTML::decode(Form::button(trans('labels.login'), [
                        'class' => 'btn-submit-login',
                        'type' => 'submit'
                        ])) }}
                {{ Form::close() }}
            </div>
        </div>
        <div class="content-footer">
            {{ HTML::link(null, trans('labels.not_member'), ['class' => 'btn-register see-more pull-left']) }}
            {{ HTML::link(action('PasswordController@postRemind'), trans('labels.forgot_pwd'), ['class' => 'see-more pull-right']) }}
            <div class="clr"></div>
        </div>
    </div>
</div>

<div id="popup-content2">
    <div class="inner-content">
        <div class="content-top">{{ trans('labels.signup') }}</div>
        <div class="content-body">
            <div class="content-body-top">
                <p>{{ trans('labels.description_popup_1') }}</p>
                <p>{{ trans('labels.other_signup') }}</p>
                <div class="socials">
                    <a href="{{ action('SocialsController@getFacebook') }}" class="ic-face"></a>
                    <a href="{{ action('SocialsController@getGoogle') }}" class="ic-google"></a>
                    <a href="{{ action('SocialsController@getGithub') }}" class="ic-git"></a>
                </div>
                <div class="clr"></div>
            </div>
            <div class="line-or">{{ trans('labels.or') }}</div>
            <div class="content-body-bottom form-content">
                <div class="message-error"></div>
                {{ Form::open(['action' => 'UsersController@postSignup', 'class' => 'viblo-signup-form', 'role' => 'form']) }}
                    {{ Form::text('name', null, ['class' => 'input-popup2 input-popup-float1', 'placeholder' => trans('labels.name_2')]) }}
                    <div class="name-error error"></div>
                    {{ Form::text('username', null, ['class' => 'input-popup2 input-popup-float2', 'placeholder' => trans('labels.username')]) }}
                    <div class="username-error error"></div>
                    <div class="clr"></div>
                    {{ Form::text('email', null, ['class' => 'input-popup2', 'placeholder' => trans('labels.email_2')]) }}
                    <div class="email-error error"></div>
                    {{ Form::password('password', ['class' => 'input-popup2 input-popup-float1', 'placeholder' => trans('labels.password_2')]) }}
                    <div class="password-error error"></div>
                    {{ Form::password('password_confirmation', ['class' => 'input-popup2 input-popup-float2', 'placeholder' => trans('labels.confirm_pwd')]) }}
                    <div class="password_confirmation-error error"></div>
                    <div class="clr"></div>
                    {{ Form::select('default_post_language',
                        ['' => trans('labels.default_post_lang')] + Config::get('detect_language.code'),
                        null,
                        ['class' => 'select-popup2 default-post-language']) }}
                    {{
                        Form::checkbox('tos', 1, true, ['class' => 'tos-check'])
                    }}
                    {{
                        trans('labels.agree_tos', [
                            'tos' => HTML::link(
                                action('UsersController@getTermsOfService'),
                                trans('labels.tos'),
                                ['class' => 'see-more', 'target' => '_blank']
                            )
                        ])
                    }}
                    <div class="clr"></div>
                    {{ HTML::decode(Form::button(trans('labels.signup'),
                        ['class' => 'btn-signup-submit',
                        'type' => 'submit'])) }}
                {{ Form::close() }}
            </div>
        </div>
        <div class="content-footer">
            {{ HTML::link(null, trans('labels.already_member'), ['class' => 'btn-login see-more']) }}
        </div>
    </div>
</div>

<div class="nth-block-1">
    <div class="container-tpage">
        <div class="tpage-header">
            <a href="/" title="Viblo" class="tpage-logo">
                <img src="{{ asset('img/logo-top-page.png') }}" alt="{{{ isset($description) ? $description : trans('app.app_description') }}}" />
            </a>
            {{ Form::select('lang',
                App\Services\LanguageService::getSystemLangMinOptions(),
                App\Services\LanguageService::getSystemLang(),
                ['id' => 'system-language-top-not-auth', 'class' => 'btn-tpage-language']) }}
            <button class="btn-tpage-log btn-login">{{ trans('labels.login') }}</button>
            <div class="clr"></div>
        </div>
        <div class="inner-container-tpage">
            <h1>{{ trans('labels.description') }}</h1>
            <p>{{ trans('labels.description_1') }}</p>

            <div class="clr"></div>
            <div class="top-login-page">
                <div class="inner">
                    <div class="col-left">
                        <div class="wrap-video">
                            <div class="video-introduce-area">
                                <div class="video-thumb">
                                    {{ HTML::image(asset('img/thumb-video.png'), null, ['class' => 'video-poster']) }}
                                    {{ HTML::image(asset('img/icon-play.png'), null, ['class' => 'video-icon-play']) }}
                                </div>
                                <div class="video-embed video">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-right">
                        <div class="tpage-box-register post">
                            <div class="top-box-register form-content">
                                <div class="message-error"></div>
                                {{ Form::open(['action' => 'UsersController@postSignup', 'class' => 'viblo-signup-form', 'role' => 'form']) }}
                                    {{ Form::text('name', null, ['class' => 'input-float1', 'placeholder' => trans('labels.name_2')]) }}
                                    <div class="name-error error"></div>
                                    {{ Form::text('username', null, ['class' => 'input-float2', 'placeholder' => trans('labels.username')]) }}
                                    <div class="username-error error"></div>
                                    <div class="clr"></div>

                                    {{ Form::text('email', null, ['class' => 'input-float3', 'placeholder' => trans('labels.email_2')]) }}
                                    <div class="email-error error"></div>
                                    {{ Form::password('password', ['class' => 'input-float1', 'placeholder' => trans('labels.password_2')]) }}
                                    <div class="password-error error"></div>
                                    {{ Form::password('password_confirmation', ['class' => 'input-float2', 'placeholder' => trans('labels.confirm_pwd')]) }}
                                    <div class="password_confirmation-error error"></div>
                                    <div class="clr"></div>

                                    {{ Form::select('default_post_language',
                                        ['' => trans('labels.default_post_lang')] + Config::get('detect_language.code'),
                                        null,
                                        ['class' => 'select-popup2 default-post-language']) }}

                                    <div class="tos-area">
                                        {{
                                            Form::checkbox('tos', 1, true, ['class' => 'tos-check'])
                                        }}
                                        {{
                                            trans('labels.agree_tos', [
                                                'tos' => HTML::link(
                                                    action('UsersController@getTermsOfService'),
                                                    trans('labels.tos'),
                                                    ['class' => 'see-more', 'target' => '_blank']
                                                )
                                            ])
                                        }}
                                        <div class="clr"></div>
                                    </div>

                                    <div class="form-btn-area">
                                    {{ HTML::decode(Form::button(trans('labels.signup'),
                                        ['class' => 'btn-signup-submit',
                                        'type' => 'submit'])) }}
                                        <div class="bottom-box-register">
                                            @if ($lang == 'ja')
                                                <p>{{ trans('labels.or_other_signup_1') }}</p>
                                                <div class="socials">
                                                    <a href="{{ action('SocialsController@getFacebook') }}" class="ic-face"></a>
                                                    <a href="{{ action('SocialsController@getGoogle') }}" class="ic-google"></a>
                                                    <a href="{{ action('SocialsController@getGithub') }}" class="ic-git"></a>
                                                </div>
                                                <p>{{ trans('labels.or_other_signup_2') }}</p>
                                            @else
                                                <p>{{ trans('labels.or_other_signup') }}</p>
                                                <div class="socials">
                                                    <a href="{{ action('SocialsController@getFacebook') }}" class="ic-face"></a>
                                                    <a href="{{ action('SocialsController@getGoogle') }}" class="ic-google"></a>
                                                    <a href="{{ action('SocialsController@getGithub') }}" class="ic-git"></a>
                                                </div>
                                            @endif
                                            <div class="clr"></div>
                                        </div>
                                    </div>
                                {{ Form::close() }}
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <!-- /.top-login-page -->

        </div>
    </div>
</div>

<div class="nth-block-2">
    <div class="container-tpage">
        <div class="inner-container-text text-lang-{{ App::getLocale() }}">
            {{ trans('labels.description_2') }}
        </div>
        <div class="inner-container-img post">
            <img src="{{ asset('img/tpage-slide02.jpg') }}" alt="{{{ isset($description) ? $description : trans('app.app_description') }}}" />
        </div>
        <div class="clr"></div>
    </div>
</div>

<div class="nth-block-3">
    <div class="container-tpage">
        <div class="inner-container-text">
            {{ trans('labels.description_3') }}
        </div>
        <div class="inner-container-img post">
            <img src="{{ asset('img/tpage-slide03.jpg') }}" alt="{{{ isset($description) ? $description : trans('app.app_description') }}}" />
        </div>
        <div class="clr"></div>
    </div>
</div>

<div class="nth-block-4">
    <div class="container-tpage">
        <div class="inner-container-text">
            {{ trans('labels.description_4') }}
        </div>
        <div class="inner-container-img post">
            <img src="{{ asset('img/tpage-slide04.jpg') }}" alt="{{{ isset($description) ? $description : trans('app.app_description') }}}" />
        </div>
        <div class="clr"></div>
    </div>
</div>

<div class="nth-block-5">
    <div class="container-tpage">
        <div class="inner-container-text">
            {{ trans('labels.description_5') }}
        </div>
        <div class="inner-container-img post">
            <img src="{{ asset('img/tpage-slide05.jpg') }}" alt="{{{ isset($description) ? $description : trans('app.app_description') }}}" />
        </div>
        <div class="clr"></div>
    </div>
</div>

<div class="nth-block-6">
    <div class="item__description text-block container-tpage">
        <img src="{{ asset('img/logo-top-page2.png') }}" alt="{{{ isset($description) ? $description : trans('app.app_description') }}}" />
        <h1 class="post">
            @if (App::getLocale() == 'ja')
                {{ trans('labels.description_footer') }}
            @else
                {{ trans('labels.description') }}
            @endif
        </h1>
        <div class="tpage-buttons post">
            <button class="tpage-btn-signup btn-sign-up">{{ trans('labels.signup') }}</button>
            <button class="tpage-btn-signin btn-login">{{ trans('labels.login') }}</button>
        </div>
  </div>
  <div class="toppage-footer container-tpage">
        <div class="toppage-footer-top">
           <p>{{ trans('labels.popular_category') }}</p>
           <div class="toppage-footer-links">
                @if ($categories->count() > 0)
                    @foreach ($categories as $key => $category)
                        <a href="{{ url_to_category($category) }}" title="{{{ $category->name }}}" class="category_not_login">{{{ $category->name }}}</a>
                    @endforeach
                @endif
            <div class="clr"></div>
           </div>
        </div>
        <div class="toppage-footer-bottom">
            <p>{{ trans('labels.language') }}:
                @foreach (App\Services\LanguageService::getSystemLangMinOptions() as $codelang => $language)
                    <a href="/{{ $codelang }}" class="{{ App\Services\LanguageService::getSystemLang() ==  $codelang ? 'active' : '' }}" title="{{ $language }}">{{ $language }}</a>
                @endforeach
            </p>
        </div>
     </div>
</div>

<div id="popup-video">
    <div class="popup-video-overlay"></div>
    <div class="popup-video-outer">
        <div class="popup-video-inner">
            <div class="popup-video-content">
                <button class="popup-video-btn-close">×</button>
                <iframe width='100%' height="{{ $agent->isMobile() ? 200 : 490 }}" src='{{{ App\Services\ImageService::getYoutubeEmbedLink(Config::get('image.video_introduction_link')) }}}' frameborder='0' allowfullscreen></iframe>
            <div class="popup-video-inner">
        </div>
    </div>
</div>
@stop
