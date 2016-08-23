<div class="col-sm-6 col-md-6 col-lg-6 pro-thumbnail">
    @if (!empty($professional->post))
        <a class="thumbnail" href="{{ url_to_post($professional->post) }}" style="background: url({{ empty($professional->professional_img) ? App\Services\HelperService::getPostThumbnail($professional->post) : '/' . $professional->professional_img }})"></a>
        <div class="pro-text">
            <a class="pro-title" href="{{ url_to_post($professional->post) }}">{{{ isset($professional->post->title) ? $professional->post->title : "" }}}</a>
            <div class="pro-info">
                <p>
                    <span class="pro-theme">{{ $monthSubject->theme_name }}</span>
                </p>
                <p>
                    <a class="pro-author" href="{{ isset($professional->post->user) ? url_to_user($professional->post->user) : '#' }}">
                        {{ (is_null($professional->post->user->profile->first_name) && is_null($professional->post->user->profile->last_name)) || (empty($professional->post->user->profile->first_name) && empty($professional->post->user->profile->last_name)) ? $professional->post->user->name : $professional->post->user->profile->first_name . ' ' . $professional->post->user->profile->last_name }}
                    </a>
                </p>
            </div>
        </div>
    @endif
</div>