<!DOCTYPE html>
<html lang="{{ App::getLocale() }}">
<head>
    @include('layouts.includes.head_toppage')
</head>

<body class="lang-{{ App::getLocale() }}">
    @yield('main')
    {{ HTML::script('js/jquery-1.11.1.min.js') }}
    @include('elements.js.app_js')
    <script type="text/javascript">
        var isMobile = "{{ $agent->isMobile() ? 1 : 0 }}";
        var errorMsg = "{{ trans('messages.error') }}";
        var noticeLabel = "{{ trans('labels.notice') }}";
        var sorryLabel = "{{ trans('labels.sorry') }}";
        var errorLabel = "{{ trans('labels.error') }}";
        var thanksLabel = "{{ trans('labels.thank_you') }}";
        var successLabel = "{{ trans('labels.success') }}";
        var confirmLabel = "{{ trans('labels.confirmation') }}";
        var yesBtn = "{{ trans('labels.yes') }}";
        var noBtn = "{{ trans('labels.no') }}";
        var okBtn = "{{ trans('buttons.ok') }}";
        var nameLabel = "{{ trans('labels.name_2') }}";
        var userNameLabel = "{{ trans('labels.username') }}";
        var emailLabel = "{{ trans('labels.email_2') }}";
        var passwordLabel = "{{ trans('labels.password_2') }}";
        var confirmPwdLabel = "{{ trans('labels.confirm_pwd') }}";
        var agreeWithTerms = "{{ trans('messages.user.agree_with_terms') }}";
    </script>
    {{ HTML::script(version('js_min/home_sign_up.min.js'), ['defer' => 'defer']) }}
</body>
</html>
