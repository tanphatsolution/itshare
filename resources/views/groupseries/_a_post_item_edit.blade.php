<div class="row link-item-element">
    <div class="series-edit form-group">
        <div class="link-viblo col-lg-11 col-md-11 col-sm-11 col-xs-10">
            <p><a href="{{ $data['url'] }}" target="_blank">
                {{ str_limit($data['url'], $limit = 110, $end = '...') }}
            </a></p>
        </div>
    </div>
    <div class="group-post-element group-content-element col-lg-11">
        <div class="group-thumbnail">
            <div class="group-box-top col-lg-3 col-md-3 col-sm-3 col-xs-3">
                <a href="{{ url_to_post($post) }}">
                    <img src="{{ asset('img/blank.png') }}" style="background: url({{ App\Services\HelperService::getPostThumbnail($post) }}) center">
                </a>
                <div class="group-mini-post-list">
                    <span class="group-post-view">{{ $post->viewsCount }}</span>
                    <span class="group-post-com">{{ $post->stocksCount }}</span>
                    <span class="group-post-favou">{{ $post->commentsCount }}</span>
                </div>
            </div>

            <div class="group-caption col-lg-9 col-md-9 col-sm-9 col-xs-9">
                <div class="group-item-info">
                    <a class="group-name-title break-word" href="{{ url_to_post($post) }}">{{{ $post->title }}}</a>
                    <div class="group-author">
                        <a href="{{ url_to_user($post->user) }}">
                            <img src="{{ asset('img/blank.png') }}" style="background: url({{ user_img_url($post->user, 100) }}) center no-repeat;">
                            <span>{{{ $post->user->name }}}</span>
                        </a>
                        <div class="group-mini-date">
                            <span>{{ trans('labels.posted_on') }}</span>
                            <span class="date">
                                {{ convert_to_japanese_date(is_null($post->published_at) ?
                                $post->updated_at : $post->published_at) }}
                            </span>
                        </div>
                    </div>

                    <p class="group-detail summary-preview">
                        {{{ \App\Services\HelperService::getPostDescription($post->getParsedContent()) }}}
                    </p>

                    <div class="tags">
                        @if (count($post->categories))
                            @foreach ($post->categories as $key => $category)
                                @if (isset($limitTotalWords))
                                    @if ($limitTotalWords >= 0 && \App\Services\PostService::CATE_POST >= $key)
                                        <a class="{{ \App\Services\HelperService::showCategoryLabel($category) }}" href="{{ url_to_category($category) }}">{{{ $category->name }}}</a>
                                    @else
                                    @endif
                                @else
                                    <a class="{{ \App\Services\HelperService::showCategoryLabel($category) }}" href="{{ url_to_category($category) }}">{{{ $category->name }}}</a>
                                @endif
                            @endforeach
                            @if ((\App\Services\PostService::CATE_TOTAL_WORDS < 0))
                                <a tabindex="0" class="other show-more-tag bs-docs-popover"
                                href="javascript:void(0);" data-trigger="focus"
                                data-toggle="popover"
                                data-content='{{ HTML::entities($moreTags) }}'>{{ trans('categories.more_symbol') }}</a>
                            @endif
                        @else
                            <a class="php" href="#">{{ trans('categories.other_title') }}</a>
                        @endif
                        </div>
                </div>
            </div>
        </div>

        {{ Form::hidden('type[]', $data['type']) }}
        {{ Form::hidden('group_post_id[]', $data['post_id']) }}
        {{ Form::hidden('group_series_title[]', $data['title']) }}
        {{ Form::hidden('group_series_description[]', '') }}
        {{ Form::hidden('group_series_thumbnail[]', $data['image']) }}
    </div>
</div>