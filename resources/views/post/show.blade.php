@extends('layouts.default')

@section('css')
{{ HTML::style(version('css_min/post_show.min.css')) }}
@stop

@section('main')
    <div class="post-detail row">
        <div class="col-md-9 col-lg-9 post-left">
            <div class="post-body">
                <p class="post-title break-word">{{{ $post->title }}}</p>
                <div class="tags author-tag">
                    <div class="tag">
                        @include('categories._category_badges', ['categories' => $post->categories, 'takeCategoryNumber' => 50])
                    </div>
                </div>
                @include('elements.share_social', ['post' => $post, 'currentUser' => $currentUser, 'element' => 'share-top'])
                @if (!$hidePostContent)
                <div class="box-post-info">
                    @if (isset($monthlyThemeSubject))
                        <div class="box-info">
                            <p>
                                @if ($lang == 'ja')
                                    {{ $monthlyThemeSubject->publish_year }}{{ trans('datetime.year_ja') }}{{ trans('datetime.month.' . $monthlyThemeSubject->publish_month) }}{{ trans('labels.monthly_theme.theme_of') }} :
                                @else
                                    {{ trans('labels.monthly_theme.theme_of') }}
                                    {{ trans('datetime.month.' . $monthlyThemeSubject->publish_month) }}
                                    {{ $monthlyThemeSubject->publish_year }} :
                                @endif
                                <a href="{{ url_to_themes($monthlyThemeSubject->short_name) }}"
                                    title="{{ $monthlyThemeSubject->theme_name }}">
                                    {{ $monthlyThemeSubject->theme_name }}
                                </a> -
                                <a class="link" href="{{ url_to_sub_theme($monthlyThemeSubject->short_name, $monthlyTheme->short_name) }}"
                                    title="{{ $monthlyTheme->themeLanguages()->first()->name }}">
                                    {{ $monthlyTheme->themeLanguages()->first()->name }}
                                </a>
                            </p>
                        </div>
                    @endif
                    <div class="post-in-theme post-info">
                        @if (in_array($lang, Config::get('country_language.JP')))
                            <span>
                                {{ convert_to_japanese_date(is_null($post->published_at) ?
                                $post->updated_at : $post->published_at, $lang) }}
                            </span>{{ trans('labels.post_in') }} {{ trans('labels.post_by') }}
                            <a href="{{{ url_to_user($post->user) }}}" class="author-link-to-profile">
                                <span>{{{ get_full_name_of_user($post->user) }}}</span>
                            </a>
                        @else
                            {{ trans('labels.post_in') }}
                            <span>
                                {{ convert_to_japanese_date(is_null($post->published_at) ?
                                $post->updated_at : $post->published_at, $lang) }}
                            </span> {{ trans('labels.post_by') }}
                            <a href="{{{ url_to_user($post->user) }}}" class="author-link-to-profile">
                                <span>{{{ get_full_name_of_user($post->user) }}}</span>
                            </a>
                        @endif
                        @if (isset($currentUser) && $currentUser->id == $post->userId)
                            @if (in_array($lang, Config::get('country_language.JP')))
                                ({{ convert_to_japanese_date(is_null($post->published_at) ?
                                $post->updated_at : $post->published_at, $lang) }}
                                {{ trans('labels.edit_in') }})
                            @else
                                ({{ trans('labels.edit_in') }} {{ convert_to_japanese_date(is_null($post->published_at) ?
                                $post->updated_at : $post->published_at, $lang) }})
                            @endif
                        @endif
                    </div>
                    <div class="btn-group">
                        @if (isset($currentUser))
                            @if ($post->canBeDeletedAndEditedBy($currentUser))
                            <a class="btn-edit" href="{{ action('PostsController@edit', $post->encryptedId); }}"
                                title="{{ trans('buttons.post.edit') }}">
                            </a>
                            <a class="btn-delete" href="javascript:void(0)"
                                data-url="{{ action('PostsController@destroy', $post->encryptedId); }}"
                                id="delete-post" data-message="{{ trans('messages.post.delete_confirm') }}"
                                data-label="{{ trans('messages.post.title_confirm') }}"
                                title="{{ trans('buttons.post.delete') }}">
                            </a>
                                @if ($post->isPublished())
                                    <a class="btn-draft" href="javascript:void(0)"
                                        data-url="{{ action('PostsController@unpublished', $post->encryptedId); }}"
                                        id="unpublished-post" data-message="{{ trans('messages.post.unpublished_confirm') }}"
                                        data-label="{{ trans('messages.post.title_confirm') }}"
                                        title="{{ trans('buttons.post.unpublished') }}">
                                    </a>
                                @endif
                            @elseif (isset($group) && $canEditPost)
                                <a class="btn-edit" href="{{ action('PostsController@edit', $post->encryptedId); }}"
                                    title="{{ trans('buttons.post.edit') }}">
                                </a>
                            @endif
                                <a href="#" class="btn-report" id="report-post"
                                   data-post-id = "{{ $post->id }}"
                                   data-title="{{ trans('messages.report.box_title') }}"
                                   data-message="<p>{{ trans('messages.report.box_header') }}</p>"
                                   data-content='
                                <label class="display-block" for="report-type-0">
                                {{ Form::radio('type', \App\Data\Blog\Report::TYPE_SPAM, true, ['id' => 'report-type-0']) }}
                                   {{ trans('messages.report.spam') }}</label><br>
                                <label class="display-block" for="report-type-1">
                                {{ Form::radio('type', \App\Data\Blog\Report::TYPE_ILLEGAL_CONTENT, false, ['id' => 'report-type-1']) }}
                                   {{ trans('messages.report.illegal_content') }}</label><br>
                                <label class="display-block" for="report-type-2">
                                {{ Form::radio('type', \App\Data\Blog\Report::TYPE_HARASSMENT, false, ['id' => 'report-type-2']) }}
                                   {{ trans('messages.report.harassment') }}</label>
                            '
                                   data-report="{{ trans('buttons.report') }}">
                                </a>
                        @endif

                    </div>
                    <div class="clearfix"></div>
                </div>
                @if ($post->isWiki())
                    <div class="wiki-nav">
                        {{ \App\Services\WikiService::getNavBar($wiki) }}
                    </div>
                @endif

                <div class="post-text-content" id="content">
                    <div class="loading text-center">
                        <p>{{ Lang::get('messages.loading') }}</p>
                    </div>
                    <section class="markdownContent cf break-word">{{ $postContent }}</section>
                </div>
                @endif
                @if ($hidePostContent)
                <div class="content-group">
                    <p>{{ trans('messages.group.join_group_notice_title') }}</p>
                    <div class="join-group">
                        {{ trans('messages.group.join_group_notice', [
                                'join' => '<a href="javascript:void(0)" id="join-group-btn"
                                    data-url="' . url_to_group($group) . '"
                                    data-group-id="' . $group->id . '" >' .
                                    trans('messages.group.join_group') . '</a>',
                                'group' => HTML::entities($group->name)
                            ])
                        }}
                    </div>
                </div>
                @endif

                @include('elements.share_social', ['post' => $post, 'currentUser' => $currentUser, 'vertical' => true])

            </div>
          <div id="comment">
              <div class="loading text-center hide comment_loading">
                  <p>{{ Lang::get('messages.loading') }}</p>
              </div>
          </div>
        </div>
        <div class="col-md-3 col-lg-3 post-right">
            <div class="bloger-like">
                <div class="list-number-social">
                    <div class="info-number">
                        <span id="stockedCount">{{ $post->numberStock }}</span>
                        <p class="">{{\App\Services\HelperService::myPluralizer(trans('labels.clip'), $post->numberStock, $lang)}}</p>
                    </div>
                    <div class="info-number">
                        <span id="commentCount">{{ $post->commentsCount }}</span>
                        <p class="">{{\App\Services\HelperService::myPluralizer(trans('labels.comment'), $post->commentsCount, $lang)}}</p>
                    </div>
                    <div class="info-number">
                        <span id="viewCount">{{ $post->viewsCount }}</span>
                        <p class="">{{\App\Services\HelperService::myPluralizer(trans('labels.view'), $post->viewsCount, $lang)}}</p>
                    </div>
                </div>
                @if (isset($currentUser))
                    <button class="btn-favorite btn-with-text {{ (($lang == 'ja') ? 'bg-btn-favor-ja' : '') }}"></button>
                @endif
                <ul class="bloger-list stockUsers">
                    @foreach($post->getLatestUsersStock() as $userStock)
                    <li class="user_{{ $userStock->id }} user-favorite-list">
                        @if (\App\Services\UserService::checkCurrentUser($currentUser, $userStock->username))
                            <a href="{{ url_to_user($userStock) }}" title="You"
                                style="background: url({{ user_img_url($userStock, 50) }})">
                            </a>
                        @else
                            <a href="{{ url_to_user($userStock) }}" title="{{{ $userStock->name }}}"
                                style="background: url({{ user_img_url($userStock, 50) }})">
                            </a>
                        @endif
                    </li>
                    @endforeach
                    @if ($post->stocksCount > \App\Data\Blog\Post::LIMIT_USER_STOCK_DISPLAY)
                    <li>
                        <a href="javascript:void(0)" id="storedCount" class="show-clipped-users"></a>
                    </li>
                    @endif
                </ul>
                <div id="modal-list-stocked-users"></div>
            </div>
            <div class="post-author">
                <div class="img-name">
                    <a href="{{ url_to_user($post->user) }}" class="author-link-to-profile"><img src="{{ user_img_url($post->user, 88) }}"></a>
                </div>
                <div class="box-info-author break-word">
                    {{{ link_to_user_with_full_name($user) }}}
                    {{ link_to_action('UsersController@getShow', '@' . $user->username, ['username' => $user->username], ['class' => 'email break-word author-link-to-profile']) }}
                    <div class="btn-post-follow">
                        <a class="btn-post-r" href="{{ url_to_user($post->user) }}">
                            {{ $post->user->publishedPosts()->count() }}
                        </a>
                        <span class="divide">|</span>
                        <a class="btn-follow-r" href="{{ URL::action('UsersController@getFollowers', $post->user->username) }}">
                            <span class="number-follow">{{ $post->user->followers()->count() }}</span>
                        </a>
                        @if (isset($currentUser))
                            <div class="btn-follow-author">
                                @include('relationships.relationships', [
                                    'currentUser' => $currentUser,
                                    'user' => $post->user
                                ])
                            </div>
                        @endif
                        <div class="clear-both"></div>
                    </div>
                </div>
            </div>
            @if(!empty($groupSeriesDetail))
                <div class="box-top-post box-more-post">
                    <p class="title-right series">{{ trans('labels.groups.related_post_series') }}</p>
                    @include('groupseries._a_series', ['groupSeriesDetail' => $groupSeriesDetail])
                </div>
            @endif
            @if (isset($group))
            <div class="box-top-post box-more-post">
                <p class="title-right">{{ trans('labels.group') }}</p>
                <div class="list-top-post list-more-post">
                    <div class="post-title top-post-detail">
                        <div class="row top-group">
                            <a href="{{ url_to_group($group) }}">
                                <img class="border-radius-50-percent" src="{{ group_img_link($group, 'profile') }}">
                            </a>
                            <div class="caption-name">
                                <a href="{{ url_to_group($group) }}"><span class="name-group break-word">{{{ $group->name }}}</span></a>
                                <ul class="mini-post">
                                    <li class="post-view">
                                        <a href="{{ route('getGroupFillter',
                                            [$group->shortname, \App\Services\GroupService::GROUP_FILTER_POST]) }}">
                                            {{ \App\Services\GroupService::getGroupContentCount($group->id)->total_posts }}
                                        </a><span class="divide">|</span>
                                    </li>
                                    <li class="post-follower">
                                        <a href="javascript:void(0)" data-id="{{ $group->id }}" class="group-member">
                                            {{ \App\Services\GroupUserService::getGroupMembers($group->id, null, true)->count() }}
                                        </a>
                                    </li>
                                </ul>
                                @if (!is_null($groupUser))
                                    @if ($groupUser->isWaiting())
                                        <button href="javascript:void(0)" data-id="{{ $group->id }}" data-flag="0" class="join-this-group">
                                            {{ trans('labels.groups.undo_request') }}
                                        </button>
                                    @endif
                                @elseif (Auth::check())
                                    <button href="javascript:void(0)" data-id="{{ $group->id }}" data-flag="1" class="join-this-group">
                                        {{ trans('labels.groups.join_group') }}
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                    @if (!is_null($postsInGroup))
                        @foreach ($postsInGroup as $postInGroup)
                        <div class="post-title break-word top-post-detail">
                            <a class="title break-word" href="{{ route('post.detail',
                                ['username' => $postInGroup->user->username, 'encryptedId' => $postInGroup->encrypted_id,])
                                }}">
                                {{{ $postInGroup->title }}}
                            </a>
                            <a class="name-author" href="{{ url_to_user($postInGroup->user) }}">
                                {{ $postInGroup->user->name }}&nbsp;-&nbsp;
                                <span>{{ convert_to_japanese_date($postInGroup->created_at, $lang) }}</span>
                            </a>
                        </div>
                        @endforeach
                        <button type="button" class="viewall"
                            Onclick="window.location.href='{{ route('getGroupFillter',
                                [$group->shortname, \App\Services\GroupService::GROUP_FILTER_POST]) }}'">
                            {{ trans('labels.groups.view_all') }}
                        </button>
                    @endif
                </div>
            </div>
            @endif
            <div class="box-top-post box-more-post">
                <p class="title-right">{{ trans('labels.pulular_posts') }}</p>
                <div class="list-top-post list-more-post">
                    @foreach($post->popular() as $key => $singlePost)
                        <div class="post-title break-word top-post-detail">
                            <a class="title break-word popular_posts_detail" number = "{{ ($key + 1) }}" href="{{ route('post.detail',
                                ['username' => $singlePost->user->username, 'encryptedId' => $singlePost->encrypted_id, ]) }}">
                                {{{ $singlePost->title }}}
                            </a>
                            <a class="name-author" number = "{{ ($key + 1) }}" href="{{ url_to_user($singlePost->user) }}">{{ $singlePost->user->name }}&nbsp;-&nbsp;
                                <span>{{ convert_to_japanese_date($singlePost->created_at, $lang) }}</span>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="box-top-post">
                <p class="title-right">{{ trans('labels.related_posts') }}</p>
                <div id="related">
                    <p class="related-loading text-center">
                        {{ Lang::get('messages.loading') }}
                    </p>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>
    <div id="modal-list-group-users"></div>
    @include('modals.code_highlight')

    @if (!Auth::check())
        @include('modals.user_register')
    @endif
@stop

@section('script')
    <script>
        @if (isset($ref))
        var ref = {{ $ref }};
        @endif
        var post_id =  {{ isset($post->id) && $post->id != null ? $post->id : "" }};
        var encrypted_id =  "{{ $post->encrypted_id }}";
        @if (isset($currentUser))
        var user_id = {{ $currentUser->id }};
        @endif
        var blogger = "{{ trans('messages.post.blogger') }}";
        var editorThemes =  {{ json_encode(\App\Data\Blog\Setting::getThemeSettingFields()) }};
        var helpful_btn = "{{ trans('messages.vote.yes') }}";
        var not_helpful_btn = "{{ trans('messages.vote.no') }}";
        var shareCountUrl = "{{ url_to_post($post) }}";
        var undoRequestText = "{{ trans('labels.groups.undo_request') }}";
        var joinThisGroupText = "{{ trans('labels.groups.join_group') }}";
        var clipThisPost = "{{ trans('labels.clip_this_post') }}";
        var removeClipped = "{{ trans('labels.remove_clipped') }}";
    </script>
    {{ HTML::script(version('js_min/codemirror.min.js'), ['defer'=>'defer']) }}
    @if (!Auth::check())
        {{ HTML::script(version('js/popup-signup.js'), ['defer'=>'defer']) }}
    @endif
    {{ HTML::script(version('js_min/post_show.min.js'),['defer'=>'defer']) }}
    {{ HTML::style(version('css_min/codemirror.min.css')) }}
@stop
