<li class="group-member">
    <a href="{{ url_to_user($user) }}" title="{{ $user->name }}">
        <img src="{{ asset('img/blank.png') }}" style="background: url({{ user_img_url($user, 300) }}) center">
    </a>
    <span class="change member-authority">
        {{ Form::select('change_authority',
            [\App\Data\Blog\GroupUser::ROLE_MEMBER => trans('labels.groups.member'), \App\Data\Blog\GroupUser::ROLE_ADMIN => trans('labels.groups.admin')],
            ($member->role == \App\Data\Blog\GroupUser::ROLE_OWNER) ? \App\Data\Blog\GroupUser::ROLE_ADMIN : $member->role,
            ['class' => 'change_authority', 'data-user-id' => $user->id,
            (Auth::check() && Auth::user()->id == $user->id) ? 'disabled' : '']) }}
    </span>
    <span class="delete member-delete">
        @if (Auth::check() && Auth::user()->id == $user->id)
            <span>{{ trans('labels.groups.member_delete') }}</span>
        @else
            <a href="javascript:void(0)" class="user-delete"
                data-user-id="{{ $user->id }}">
                {{ trans('labels.groups.member_delete') }}
            </a>
        @endif
    </span>
</li>