<?php $randomGroupUsers = [] ?>
@if (isset($follow) && $follow)
    <?php $groupUsers = \App\Services\GroupUserService::getUserFollowInGroups($group->id, $followerUsers) ?>
@else
    <?php $groupUsers = \App\Services\GroupUserService::getGroupMembers($group->id,null,true) ?>
@endif
<?php $groupUserTotal = count($groupUsers->toArray()) ?>
<ul class="like-article">
    @if ($groupUserTotal > 4)
        @if (isset($follow) && $follow)
            @foreach ($groupUsers as $key => $groupUser)
                @if ($key >= 4)
                    {{ ''; break }}
                @endif

                @if (isset($groupUser->user->name) && $groupUser->user->name != null)
                <li>
                    <a title="{{{ $groupUser->user->name }}}" href="{{ url_to_user($groupUser->user) }}">
                        <img style="background: url({{ user_img_url($groupUser->user, 50) }}) center" src="{{ asset('img/blank.png') }}">
                    </a>
                </li>
                @endif
                
                <?php unset($groupUsers[$key]) ?>
            @endforeach
        @else
            <?php $randomGroupUsers = array_rand($groupUsers->toArray(), 4) ?>
            <?php $showMember = [] ?>
            @foreach ($randomGroupUsers as $randomGroupUser)
                @if (isset($randomGroupUser->user->name) && $randomGroupUser->user->name != null)
                    <li>
                        <a title="{{{ $groupUsers[$randomGroupUser]->user->name }}}" href="{{ url_to_user($groupUsers[$randomGroupUser]->user) }}">
                            <img style="background: url({{ user_img_url($groupUsers[$randomGroupUser]->user, 50) }}) center" src="{{ asset('img/blank.png') }}">
                        </a>
                    </li>
                @endif
                <?php unset($groupUsers[$randomGroupUser]) ?>
            @endforeach
        @endif
    @else
        @foreach ($groupUsers as $key => $groupUser)
            @if (!empty($groupUser) && isset($groupUser->user->name) && $groupUser->user->name != null)
                <li>
                    <a title="{{{ $groupUser->user->name }}}" href="{{ url_to_user($groupUser->user) }}">
                        <img style="background: url({{ user_img_url($groupUser->user, 50) }}) center" src="{{ asset('img/blank.png') }}">
                    </a>
                </li>
            @endif
        @endforeach
    @endif
    @if ($groupUserTotal > 4)
        <li class="quantity-like">
            <a href="javascript:void(0)" @if (isset($ajax) && $ajax) onclick="listAllUserJoinGroup(this)" @endif data-id="{{ $group->id }}" class="group_member" data-toggle="modal">
                +{{ count($groupUsers) }}
            </a>
        </li>
    @endif
</ul>
