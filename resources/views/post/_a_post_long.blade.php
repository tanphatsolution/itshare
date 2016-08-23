<div class="post-long col-lg-12">
    <div class="pull-left">
        {{ user_img_tag($post->user, 40) }}
    </div>
    <div class="post-info pull-left">
        <div class="row">
            {{ link_to_user($post->user) }} {{ trans('messages.post.posted') }}
            <span class="text-info">{{ $post->created_at->diffForHumans() }}</span>
        </div>
        <div class="row post-title">
            {{ link_to_post($post) }}
        </div>
    </div>
</div>
<br>
<div class="line"></div>