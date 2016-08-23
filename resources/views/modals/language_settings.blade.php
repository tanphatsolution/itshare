<div class="modal select-language-modal fade" id="modal-language-settings" tabindex="-1" role="dialog" aria-labelledby="language-modal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" data-dismiss="modal">
                    <span aria-hidden="true"></span>
                    <span class="sr-only">{{ trans('buttons.close') }}</span>
                </button>
                <h4 class="modal-title">{{ trans('modals.language') }}</h4>
            </div>

            <div class="modal-body">
                <div class="language-box">
                    <p class="title">{{ trans('messages.setting.post_language') }}</p>

                    <div class="alert-dismissable alert-info"></div>

                    <div class="language-box-content form-filter-post-language">
                        <form class="post-filter-language">
                            <ul class="language-list-items languages">
                                @include('setting._list_languages', ['languages' => $languages])
                            </ul>
                        </form>

                        <button id="add-language" class="btn btn-default btn-sm add-language">{{ trans('labels.add_language') }}</button>
                        {{ Form::button(trans('buttons.chose_all'),
                          ['class' => 'btn btn-yellow btn-sm selected-all-languages',
                          'id' => 'selected-all-languages',
                          'data-type' => 'allLanguages']) }}
                    </div>
                </div>

                <div class="language-box system-language-setting">
                    <p class="title">{{ trans('messages.setting.system_language') }}</p>

                    <div class="alert-dismissable alert-info"></div>

                    <div class="language-box-content">
                        {{ Form::select('lang',
                            App\Services\LanguageService::getSystemLangOptions(),
                            $setting->lang,
                            ['id' => 'system-language']) }}
                    </div>
                </div>

                <div class="language-box default-create-post-language">
                    <p class="title">{{ trans('messages.setting.default_post_language') }}</p>

                    <div class="alert-dismissable alert-info"></div>

                    <div class="language-box-content">
                        {{ Form::select('default_post_language',
                            \Config::get('detect_language.code'),
                            $setting->default_post_language,
                            ['id' => 'default-post-language',
                            'data-type' => 'default']) }}

                        {{ Form::button(trans('buttons.apply_to_all_posts'),
                            ['class' => 'btn btn-yellow btn-sm',
                            'name' => 'changeLanguageAllPosts',
                            'id' => 'change-default-all-post-language',
                            'data-type' => 'defaultAll']) }}
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="inner">
                    <button class="btn btn-default btn-black btn-close-modal" data-dismiss="modal">{{ trans('buttons.close') }}</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- include javascript for ajax call server get setting languages -->
@include('modals.language_setting_js')