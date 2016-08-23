<!-- Login Modal -->
<link rel="stylesheet" href="/css/popup-toppage.css" media="none" onload="if(media!='all')media='all'">
<div class="modal fade inner-content" id="viblo-login-dialog" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog basicpopup-content" >
        <div id="popup-content" class="popup-content">
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
                    {{ HTML::link('#', trans('labels.not_member'), ['class' => 'btn-register btn-form-register see-more pull-left']) }}
                    {{ HTML::link(action('PasswordController@postRemind'), trans('labels.forgot_pwd'), ['class' => 'see-more pull-right']) }}
                    <div class="clr"></div>
                </div>
            </div>
        </div>

        <div id="popup-content2" class="popup-content">
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
                    {{ HTML::link('#', trans('labels.already_member'), ['class' => 'btn-login btn-form-login see-more']) }}
                </div>
            </div>
        </div>
    </div>
</div>
{{ HTML::script(version('js/toppage.js'), ['defer' => 'defer']) }}