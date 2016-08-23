<div class="list-top-post">
    @foreach($postRelated as $key => $singlePost)
        <div class="row top-post-detail">
            <a class="col-sm-4 col-md-4 col-lg-4 top-post-img related_posts_detail"
               href="{{ url_to_post($singlePost) }}" number="{{ ($key + 1) }}">
                <img src="{{ asset('img/blank.png') }}" style="background: url({{ \App\Services\HelperService::getPostThumbnail($singlePost) }})">
            </a>
            <div class="col-sm-8 col-md-8 col-lg-8 top-post-content break-word">
                <a class="title break-word related_posts_detail" number = "{{ ($key + 1) }}"  href="{{ link_to_post($singlePost) }}" title="{{ $singlePost->title }}">{{{ $singlePost->title }}}</a>
                <a href="{{ url_to_user($singlePost->user) }}" class="name"
                   title="{{{ link_to_user($singlePost->user) }}}">{{{ $singlePost->user->name }}}</a>
            </div>
        </div>
    @endforeach
</div>