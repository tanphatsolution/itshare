@extends('layouts.default')

@section('css')
{{ HTML::style(version('css_min/post_edit.min.css')) }}
{{ HTML::style(version('css_min/codemirror.min.css')) }}
@stop

@section('main')

<div class="col-sm-12 post create-post" data-is-published="{{ $post->isPublished() }}">
    @if ($errors->has())
        <div class='alert alert-danger'>
            @foreach($errors->all() as $message)
                <p>{{ $message }}</p>
            @endforeach
        </div>
    @endif
    {{ Form::open(['action' => ['PostsController@update', $post->encryptedId], 'method'=>'PATCH', 'id' => 'edit-post']) }}
        <div class="col-md-12">
            <div class="row btn-choice-upload">
                <div class="col-sm-12 l-screen no-padding-right">
                    <input type="hidden" name="encrypted_id" id="encrypted_id" value="{{$post->encrypted_id}}">
                    {{ Form::text('title', $post->title, ['id' => 'title', 'class' => 'form-control', 'placeholder' => 'Title']) }}
                </div>
            </div>
            <div class="row choice-category">
                <div class="col-sm-10 l-screen">
                    {{ Form::text(
                        'category',
                        implode(',', $post->getCategoriesName()),
                        ['class' => 'form-control', 'placeholder' => trans('messages.post.enter_categories'), 'id' => 'category-input']
                    ) }}
                </div>
                <div class="col-sm-2 l-group-screen">
                    {{ Form::select('group_id',
                        ['0' => trans('labels.groups.choice_group')] + $groups,
                        is_null($groupPost) ? null : $groupPost->group_id,
                        ['id' => 'group-id',
                        !$isAuthor ? 'disabled' : '',
                        'class' => 'form-control']) }}
                    {{ Form::hidden('group_id_hidden', is_null($groupPost) ? null : $groupPost->group_id) }}
                </div>
            </div>
            <div class="row advance-option col-md-12">
                <div class="form-group no-margin-bottom">
                    <div class="advance">
                        <span class="btn-advance">{{ trans('labels.advance_option') }}</span>
                    </div>
                    <div class="form-tag">
                        @foreach ($topCategories as $category)
                            <a class="topCategories" data-category="{{{ $category->name }}}" href="javascript:void(0)" title="{{{ $category->name }}}">
                                {{{ $category->name }}}
                            </a>
                        @endforeach
                    </div>
                    <div class="list-advance">
                        {{ Form::select('language_code',
                            ['' => trans('messages.setting.select_language')] + Config::get('detect_language')['code'],
                            $post->language_code, ['id' => 'language_code']) }}
                        {{ Form::select('privacy_flag',
                           array_merge(['' => trans('labels.groups.choice_group_post_privacy')], App\Services\GroupService::groupPostPrivacyOptions()),
                            is_null($groupPost) ? null : $groupPost->privacy_flag, ['id' => 'group-post-privacy']) }}
                        <div class="monthly-theme-subject">

                            {{ Form::select('monthly_theme_subject_id',
                                array_merge([0 => trans('messages.theme.select_theme_subject')], $themes['monthlyThemeSubjects']->toArray()),
                                isset($themes['monthlyThemeSubjectId']) ? $themes['monthlyThemeSubjectId'] : null,
                                ['onChange' => 'getMonthlyThemes()', 'id' => 'monthly-theme-subject-id']) }}
                            {{ Form::hidden('hiddenThemeId', $themes['themeId'], ['id' => 'hidden-theme-id']) }}
                        </div>
                        <div class="themes-in-month"></div>
                        @if (!$post->published_at)
                            <span>{{ trans('labels.any_one') }}:</span>
                            {{ Form::select('share_by_url',
                                \App\Services\PostService::getShareDraftOption(),
                                $post->share_by_url,
                                ['id' => 'share-by-url']) }}
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="post-body-wrapper half-previewing" id="postBodyWrapper">
                    <div class="post-body-markdown">
                        <div class="form-group padding-left-content">
                            <div class="col-sm-2 col-xs-6 pdl-0" data="theme-select">
                                <select class="form-control remove-screen-effect" id="theme-selector">
                                    <option>{{ trans('labels.monthly_theme.theme') }}</option>
                                    @foreach ($editorThemeList as $editorTheme)
                                        <option>{{ $editorTheme }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-2 col-xs-6 pdl-0" data="language-select">
                                <select class="form-control remove-screen-effect" id="language-selector">
                                    <option>{{ trans('labels.highlight') }}</option>
                                </select>
                            </div>
                            <div class="btn-group">
                                <div class="btn btn-default editorButton" data="bold" title="{{ trans('labels.bold') }}"><b>B</b></div>
                                <div class="btn btn-default editorButton" data="italic" title="{{ trans('labels.italic') }}"><i>I</i></div>
                                <div class="btn btn-default editorButton" data="heading" title="{{ trans('labels.heading') }}">H</div>
                            </div>
                            <div class="btn-group">
                                <div class="btn btn-default editorButton" data="order_list" title="{{ trans('labels.order_list') }}">
                                    <span class="glyphicon glyphicon-list" aria-hidden="true"></span>
                                </div>
                                <div class="btn btn-default editorButton" data="code" title="{{ trans('labels.code') }}">
                                    <span class="glyphicon glyphicon-asterisk" aria-hidden="true"></span>
                                </div>
                                <div class="btn btn-default editorButton" data="quote" title="{{ trans('labels.quote') }}">
                                    <span class="glyphicon glyphicon-comment" aria-hidden="true"></span>
                                </div>
                            </div>
                            <div class="btn btn-default" data-toggle="modal" data-target="#myModal" title="{{ trans('labels.markdown.help') }}">
                                Markdown
                                <span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span>
                            </div>
                            <div class="btn btn-default" data-toggle="modal" data-target="#textileHelpModal" title="{{ trans('labels.title.help') }}">
                                {{ trans('labels.textile.title') }}
                                <span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span>
                            </div>
                        </div>
                        {{ Form::hidden(
                            'content',
                            $post->content,
                            [
                                'id' => 'content-hidden',
                                'class' => 'markdown-content-hidden',
                                'data-provide' => 'markdown',
                            ]
                        ) }}
                        {{ Form::textarea(
                            'content_show',
                            $post->content,
                            [
                                'id' => 'editor',
                                'class' => 'form-control markdown',
                                'rows' => '20',
                                'placeholder' => 'Content..',
                                'data-provide' => 'markdown',
                            ]
                        ) }}

                    </div>
                    <div class="post-body-preview">
                        <div class="preview-navigation">
                            <i class="fa fa-angle-double-left full-preview preview-nav"></i>
                            <i class="fa fa-angle-left half-preview-left preview-nav"></i>
                            <i class="fa fa-angle-right half-preview-right preview-nav"></i>
                            <i class="fa fa-angle-double-right close-preview preview-nav"></i>
                        </div>
                        <div class="preview markdownContent post-content" id="screen_preview"></div>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
        <div class="upload_img">
            <div class="btn btn-default" data="image-upload" id="image-uploader" data-title="{{ trans('labels.image_uploader') }}" title="{{ trans('labels.image_uploader') }}">
                <img src="{{ asset('img/icon-upload-defaul.png') }}" class="icon-upload-image" alt="{{{ trans('labels.image_uploader') }}}" />
                {{ trans('labels.select_image') }}
            </div>
        </div>
        <div class="row btn-submit" style="width: 48%; float: right">
            {{ HTML::decode(Form::submit(trans('buttons.post.publish'),
                ['class' => 'publish', 'name' => 'publish', 'onclick' => "javascript:ga('send', 'event', 'button', 'click', 'post', 1);"])) }}
            @if (!$post->published_at)
                {{ Form::submit(trans('buttons.post.save'), ['class' => 'btn btn-draft', 'name' => 'save']) }}
            @endif
        </div>
    {{ Form::close() }}
    @include('modals.markdown_help')
    @include('modals.textile_help')
</div>

@stop

@section('script')
    <script type="text/javascript">
        var categories =  {{ json_encode($categories) }};
        var editorThemes =  {{ json_encode(\App\Data\Blog\Setting::getThemeSettingFields()) }};
        @if (isset($currentUser))
            var userTheme =  {{ $currentUser->setting->viblo_theme ? $currentUser->setting->viblo_theme : "undefined" }};
        @endif
        var postPublic =  {{ \App\Data\Blog\GroupPost::GROUP_POST_PUBLIC }};
        var postPrivate = {{ \App\Data\Blog\GroupPost::GROUP_POST_PRIVATE }};
        var groupPrivacyProtected = {{ (is_null($groupPrivacyProtected)) ? 0 : 1 }};
        var isAuthor = {{ ($isAuthor) ? 1 : 0 }};
        var thumbnailFlag = {{ !empty($post->thumbnail) ? 1 : 0 }};
        var closedPrivate = "{{ trans('labels.groups.closed_private') }}";
        var secretPrivate = "{{ trans('labels.groups.secret_private') }}";
    </script>
    {{ HTML::script(version('js_min/codemirror.min.js'), ['defer'=>'defer']) }}
    {{ HTML::script(version('js_min/post_edit.min.js'), ['defer'=>'defer']) }}
@stop
