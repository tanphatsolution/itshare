{{ HTML::script(version('/js_min/layout_head.min.js')) }}
@include('elements.js.app_js')
<script type="text/javascript">
    var supportedLanguages = {{ json_encode(App\Data\Blog\Setting::getSupportedLanguages()) }};
    var highlightModeMappingArr = {{ json_encode(App\Data\Blog\Setting::getSupportedLanguageModes()) }};
    var aLanguage = {{ json_encode(\View::make('setting._a_language', ['language' => null, 'zeroOpacity' => true])->render()) }};
    var maxLanguages = {{ count(\Config::get('detect_language.code')) }};
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
    var youSureMsg = "{{ trans('messages.post.title_confirm') }}";
    var undoRequest = "{{ trans('labels.groups.undo_request') }}";
    var joinGroupLabel = "{{ trans('labels.groups.join_group') }}";
    var loading = "{{ trans('messages.loading') }}";
    var loadMore = "{{ trans('labels.load_more') }}";
    var nameLabel = "{{ trans('labels.name_2') }}";
    var userNameLabel = "{{ trans('labels.username') }}";
    var emailLabel = "{{ trans('labels.email_2') }}";
    var passwordLabel = "{{ trans('labels.password_2') }}";
    var confirmPwdLabel = "{{ trans('labels.confirm_pwd') }}";
    var agreeWithTerms = "{{ trans('messages.user.agree_with_terms') }}";
    var confirmDelete = "{{ trans('messages.group.confirm_delete') }}";
</script>
{{ HTML::script(version('js_min/layout_head2.min.js'), ['defer'=>'defer']) }}
{{ HTML::script(version('js/node/client.js'), ['defer'=>'defer']) }}
{{ HTML::script('js/node/socket.iov100.js', ['defer'=>'defer']) }}
@yield('script')
