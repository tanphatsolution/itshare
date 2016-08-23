<li class="group-member">
    <a href="{{ url_to_user($user) }}" title="{{ $user->name }}">
        <img src="{{ asset('img/blank.png') }}" style="background: url({{ user_img_url($user, 300) }}) center">
    </a>
    {{ Form::hidden('membersId[]', $user->id) }}
    <span class="delete member-remove">
        <a href="javascript:void(0)" class="user-remove">
            {{ trans('labels.groups.remove_member') }}
        </a>
    </span>
</li>