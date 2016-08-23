<div class="box-top-post">
    <p class="title-right">{{ trans('labels.pulular_posts') }}</p>
    <div class="list-top-post">
        @foreach($populars as $singlePost)
            @if (isset($singlePost->user->name) &&  $singlePost->user->name != null)
                <div class="row top-post-detail">
                    <a class="col-md-4 col-lg-4 top-post-img" href="{{ link_to_post($singlePost) }}">
                        <img src="{{ asset('img/blank.png') }}"
                             style="background: url({{ \App\Services\HelperService::getPostThumbnail($singlePost, \App\Services\HelperService::THUMBNAIL_RIGHT_SIZE) }})"
                             alt="{{{ $singlePost->title }}}"/>
                    </a>
                    <div class="col-md-8 col-lg-8 top-post-content break-word">
                        <a class="title"  href="{{link_to_post($singlePost)}}" title="{{ $singlePost->title }}">{{{ $singlePost->title }}}</a>
                        <a href="{{ url_to_user($singlePost->user) }}" class="name"
                           title="{{{ $singlePost->user->name }}}">{{{ $singlePost->user->name }}}</a>
                    </div>
                </div>
            @endif
        @endforeach
    </div>
</div>
