
@extends('layouts.default')

@section('css')
    {{ HTML::style(version('css_min/post_index.min.css')) }}
@stop

@section('main')
    <div class="post-detail all-post row">
        <div class="col-md-9 col-lg-9 post-left"> 
            @if (Session::has('success'))
                <div class="alert alert-success text-center" role="alert">
                    <p>{{ Session::get('success') }}</p>
                </div>
            @endif

            <div class="box-title box-title-child">
                <ul class="list-title" id="posts_tablist">
                    @if (empty($class['clip']))
                        <li role="presentation">
                            <a href="{{ route('getPostWall', '') }}" class = "{{ $class['all'] }}">
                            {{ trans('categories.new_posts') }}
                            </a>
                        </li>
                        <li role="presentation">
                            <a href="{{ route('getPostWall', \App\Services\PostService::WALL_FEED) }}" class = "{{ $class['follow'] }}">
                            {{ trans('categories.follow') }}
                            </a>
                        </li>
                        <li role="presentation">
                            <a href="{{ route('getPostWall', \App\Services\PostService::WALL_RECENT) }}" class = "{{ $class['top_clips'] }}">
                            {{ trans('categories.recently_stocked_posts') }}
                            </a>
                        </li>
                        <li role="presentation">
                            <a href="{{ route('getPostWall', \App\Services\PostService::WALL_TOP) }}" class = "{{ $class['top_posts'] }}">
                            {{ trans('categories.top_posts') }}
                            </a>
                        </li>
                        <li role="presentation">
                            <a href="{{ route('getPostWall', \App\Services\PostService::WALL_HELPFUL) }}" class = "{{ $class['helpful'] }}">
                            {{ trans('labels.post_all.top_helpful') }}
                            </a>
                        </li>
                    @endif
                    <li role="presentation">
                            <a href="{{ route('getPostWall', \App\Services\PostService::WALL_STOCK) }}" class = "{{ $class['stock'] }}">
                            {{ trans('labels.stock') }}
                            </a>
                    </li>
                </ul>

                {{ Form::open(['method' => 'get', 'action' => 'PostsController@filter', 'class' => 'filter' . (!!empty($class['clip']) ? ' pull-right' : '')]) }}
                    <div class="filter">
                        {{ Form::select('filter_by',
                            [
                                \App\Services\PostService::SELECT_FILTER => trans('labels.select_filter'),
                                \App\Services\PostService::FILTER_BY_THIS_MONTH_STRING => trans('labels.this_month'),
                                \App\Services\PostService::FILTER_BY_LAST_MONTH_STRING => trans('labels.last_month'),
                            ],
                            $filterBy,
                            ['id' => 'post_filter']
                        ) }}
                    </div>
                {{ Form::close() }}
                <div class="clear-both"></div>
            </div>

            <div class="row blog-post post-list">
                @if ($wall == \App\Services\PostService::WALL_ALL)
                    @include('post._list_posts_series', ['posts' => $posts])
                @else
                    @include('post._list_posts_title_tags', ['posts' => $posts, 'currentUser' => $currentUser])
                @endif
            </div>

            @if ($posts->count() >= \App\Services\PostService::PER_PAGE)
                <div class="load-more fake-link all-post">
                    <a id="seeMorePost" data-message="{{ trans('messages.loading') }}" title="{{ trans('labels.load_more') }}">{{ trans('labels.load_more') }}</a>
                </div>
            @endif
        </div>
        <div class="col-md-3 col-lg-3 post-right">
            @if (!empty($topStocked) && (count($topStocked) >= \App\Data\Blog\Post::MIN_HOT_POSTS_TO_DISPLAY))
                <div class="box-top-post">
                    <p class="title-right">{{ trans('labels.hot_posts') }}</p>
                    <div class="list-top-post">
                        @forelse ($topStocked as $stockedPost)
                            <div class="row top-post-detail">
                                <a class="col-md-4 col-lg-4 top-post-img" href="{{ url_to_post($stockedPost) }}" title="{{{ $stockedPost->title }}}">
                                    <img src="{{ asset('img/blank.png') }}" style="background: url({{ App\Services\HelperService::getStockedPostThumbnail($stockedPost) }})" alt="{{{ $stockedPost->title }}}" />
                                </a>
                                <div class="col-md-8 col-lg-8 top-post-content break-word">
                                    <a class="title" title="{{ $stockedPost->title }}" href="{{url("$stockedPost->username/posts/$stockedPost->encrypted_id")}}">{{{ $stockedPost->title }}}</a>
                                    <a href="{{ url("users/show/$stockedPost->username")}}" class="name"
                                       title="{{{ $stockedPost->username }}}">{{{ $stockedPost->username }}}</a>
                                </div>
                            </div>
                        @empty
                            <div class="alert alert-warning" role="alert">
                                {{ trans('messages.post.message_warning')}}
                            </div>
                        @endforelse
                    </div>
                </div>
            @else
                @include('post._popular_posts', ['populars' => $populars])
            @endif

            <div class="box-top-post top-author group-categories">
                <p class="most-post-categories-title">{{ trans('labels.top_post_categories') }}</p>
                <div class="group-categories-list">
                    @if (isset($topCategories) && $topCategories != null)
                        <ul class="category">
                            @foreach ($topCategories as $singleCategory)
                                <li>
                                    <a href="{{ url_to_category($singleCategory) }}">
                                        {{{ $singleCategory->short_name }}}
                                    </a>
                                    <span class="sum-article">{{ $singleCategory->categories_count }}</span>
                                </li>
                            @endforeach
                            @if ($topCategories->count() >= \App\Data\Blog\Category::TOP_CATEGORIES_POST_LIMIT)
                                <div>
                                    <a class="most_posts_tag" href="{{ action('CategoriesController@index') }}">
                                        {{ trans('labels.view_all') }}...
                                    </a>
                                </div>
                            @endif
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
@stop

@section('script')
    <script type="text/javascript">
        var wall = '{{ $wall }}';
        var seoLang = '{{ $seoLang }}';
        var editorThemes =  {{ json_encode(App\Data\Blog\Setting::getThemeSettingFields()) }};
    </script>
    {{ HTML::script(version('js_min/codemirror_home.min.js'), ['defer' => 'defer']) }}
    {{ HTML::script(version('js_min/post_index.min.js'), ['defer' => 'defer']) }}
@stop
