@if ($top_posts && $top_posts->count() > 0)
    <div class="box-top-post">
        <p>{{ $label }}</p>
        <div class="list-top-post">
            @foreach($top_posts as $singlePost)
                <div class="row top-post-detail">
                    <a class="col-md-4 col-lg-4 top-post-img" href="{{ url_to_post($singlePost) }}" title="{{{ $singlePost->title }}}">
                        <img src="{{ asset('img/blank.png') }}" style="background: url({{ App\Services\HelperService::getPostThumbnail($singlePost) }})" alt="{{{ $singlePost->title }}}" />
                    </a>
                    <div class="col-md-8 col-lg-8 top-post-content break-word">

                        <a class="title"  href="{{link_to_post($singlePost)}}" title="{{ $singlePost->title }}">{{{ $singlePost->title }}}</a>
                        <a href="{{ url_to_user($singlePost->user) }}" class="name"
                           title="{{{ link_to_user($singlePost->user) }}}">{{{ $singlePost->user }}}</a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif