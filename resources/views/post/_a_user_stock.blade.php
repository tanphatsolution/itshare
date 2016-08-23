<li class="user_{{ $userStock->id }} user-favorite-list">
    <a href="{{ url_to_user($userStock) }}" title="{{{ $userStock->name }}}" style="background: url({{ user_img_url($userStock, 50) }})"></a>
</li>
