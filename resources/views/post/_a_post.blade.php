@if (!empty($post->user))
    <div class="col-lg-12">
        <div class="thumbnail">
            <div class="box-top">
                <a href="{{ url_to_post($post) }}" class="name-title">
                    <img src="data:image/png;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs="  class="lazy center" data-original = "{{ App\Services\HelperService::getPostThumbnail($post) }}">
                </a>
                <div class="mini-post-list">
                    <span class="post-view">{{ $post->viewsCount }}</span>
                    <span class="post-com">{{ $post->commentsCount }}</span>
                    <span class="post-favou">{{ $post->stocksCount }}</span>
                </div>
            </div>

            <div class="caption">
                <div class="item-info">
                    <a class="name-title break-word" title="{{{ $post->title }}}" href="{{ url_to_post($post) }}">{{{ $post->title }}}</a>
                    <div class="author">
                        <a href="{{ url_to_user($post->user) }}" class="author-link-to-profile">
                            <img src="data:image/png;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=" class="lazy center" data-original ={{ user_img_url($post->user, 20) }}>
                            <span>{{{ get_full_name_of_user($post->user) }}}</span>
                        </a>

                        <div class="mini-date">
                            <span>{{ trans('labels.posted_on') }}</span>
                            <span class="date">
                                {{ convert_to_japanese_date(is_null($post->published_at) ?
                                $post->updated_at : $post->published_at, $lang) }}
                            </span>
                        </div>
                    </div>

                    <p class="detail break-word">
                        {{ App\Services\HelperService::getPostDescription($post->getParsedContent()) }}
                    </p>

                    @include('categories._category_badges', ['categories' => $post->categories, 'takeCategoryNumber' => App\Services\PostService::CATE_POST, 'limitTotalWords' => App\Services\PostService::CATE_TOTAL_WORDS])
                </div>
            </div>
        </div>
    </div>
@endif