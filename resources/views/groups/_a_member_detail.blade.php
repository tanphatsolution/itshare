@if (isset($member->user->name) && $member->user->name != null)
    <li class="group-member-detail">
        <a href="{{ url_to_user($member->user) }}" title="{{{ $member->user->name }}}">
            <img src="{{ asset('img/blank.png') }}" style="background: url({{ user_img_url($member->user, 100) }}) center">
        </a>
    </li>
@endif