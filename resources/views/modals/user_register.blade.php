<div id="pop-up" class="pop container-fluid">
    <div class="close-popup-signup">
        <a href="javascript:void(0)">x</a>
    </div>
    <div class="l-popup-register container">
        <h3>{{ trans('labels.popup_see_more') }}</h3>
        <p class="l-popup-login">{{ trans('labels.description_1') }}</p>
        <button type="button" class="register" data-toggle="modal" data-target="#modal-popup-signup">
            {{ trans('labels.register') }}
        </button>
        <button type="button" class="login" data-toggle="modal" data-target="#modal-popup-login">
            {{ trans('labels.login') }}
        </button>
        <div class="modal fade" id="modal-popup-login" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog l-box-login" role="document">
                <div class="modal-content">
                    <div class="modal-header-login">{{ trans('labels.login') }}</div>
                    <div class="modal-body-login form-content">
                        <p class="join">{{ trans('labels.other_signup') }}</p>
                        <ul class="list-social-login">
                            <li><a href="{{ action('SocialsController@getFacebook') }}" class="facebook"></a></li>
                            <li><a href="{{ action('SocialsController@getGoogle') }}" class="google"></a></li>
                            <li><a href="{{ action('SocialsController@getGithub') }}" class="github"></a></li>
                        </ul>
                        <div class="l-or">
                            <span>or</span>
                        </div>
                        <div class="message-error"></div>
                        {{ Form::open(['action' => 'UsersController@postLogin', 'class' => 'viblo-login-form', 'role' => 'form']) }}
                        {{ Form::hidden('return',
                            (URL::current() == URL::to('/')) ? action('HomeController@getTopPage') : URL::current()) }}
                        {{ Form::text('username', null,
                            ['id' => 'txtUsername', 'placeholder' => trans('labels.username') . ' ' . trans('labels.or') . ' ' . trans('labels.email'),
                            'class' => 'form-control popup-email']) }}
                        {{ Form::password('password',
                            ['class' => 'form-control popup-pass',
                            'placeholder' => trans('labels.password')]) }}
                        <div class="l-check">
                                <span class="check">
                                    {{ Form::checkbox('remember', 1, true) }} {{ trans('labels.remember_me')}}
                                </span>
                            <a href="{{ action('PasswordController@postRemind') }}"
                               class="forgot">{{ trans('labels.forgot_pwd') }}</a>
                        </div>
                        <input class="btn-login btn-submit-login" type="submit" value="{{ trans('labels.login') }}">
                        {{ Form::close() }}
                    </div>
                    <div class="modal-footer-login">
                        <p>{{ trans('labels.not_member') }}</p>
                        <button type="button" class="btn-register-modal">{{ trans('labels.register') }}</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="modal-popup-signup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog l-box-register" role="document">
                <div class="modal-content">
                    <div class="modal-header-register">{{ trans('labels.register') }}</div>
                    <div class="modal-body-register form-content">
                        <p class="join">{{ trans('labels.other_signup') }}</p>
                        <ul class="list-social-register">
                            <li><a href="{{ action('SocialsController@getFacebook') }}" class="facebook"></a></li>
                            <li><a href="{{ action('SocialsController@getGoogle') }}" class="google"></a></li>
                            <li><a href="{{ action('SocialsController@getGithub') }}" class="github"></a></li>
                        </ul>
                        <div class="l-or">
                            <span>or</span>
                        </div>
                        <div class="message-error"></div>
                        {{ Form::open(['action' => 'UsersController@postSignup', 'class' => 'viblo-signup-form', 'role' => 'form']) }}
                        {{ Form::text('name', null,
                            ['class' => 'form-control yourname', 'placeholder' => trans('labels.name_2')]) }}
                        {{ Form::text('username', null,
                            ['class' => 'form-control username', 'placeholder' => trans('labels.username')]) }}
                        {{ Form::text('email', null,
                            ['class' => 'form-control email', 'placeholder' => trans('labels.email_2')]) }}
                        {{ Form::password('password',
                            ['class' => 'form-control password', 'placeholder' => trans('labels.password_2')]) }}
                        {{ Form::password('password_confirmation',
                            ['class' => 'form-control repassword', 'placeholder' => trans('labels.confirm_pwd')]) }}
                        <div class="l-check">
                            <div class="check">
                                <input type="checkbox" class="tos-check" name="tos">
                                <span>{{ trans('labels.agree_tos', ['tos' => trans('labels.tos')]) }}</span>
                            </div>
                            <a href="{{ action('UsersController@getTermsOfService') }}" class="seemore" target="_blank">
                                {{ trans('labels.see_more') }}
                            </a>
                        </div>
                        <input class="btn-register btn-signup-submit" type="submit"
                               value="{{ trans('labels.signup') }}">
                        {{ Form::close() }}
                    </div>
                    <div class="modal-footer-register">
                        <p>{{ trans('labels.already_member') }}</p>
                        <button type="button" class="btn-login-modal">{{ trans('labels.login') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>