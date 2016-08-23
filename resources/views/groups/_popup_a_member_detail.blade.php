<li>
    <img src="{{ asset('img/blank.png') }}" style="background: url({{ user_img_url($member->user, 100) }}) center no-repeat;">
    <div class="caption-name">
        <a href="{{ url_to_user($member->user) }}" class="name">{{ $member->user->name }}</a>
        <span class="post-view">{{ $member->user->publishedPosts()->count() }}</span>
        <span class="post-favou">{{ $member->user->followers()->count() }}</span>
    </div>
</li>