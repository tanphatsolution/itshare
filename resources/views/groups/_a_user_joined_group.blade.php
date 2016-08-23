<div class="list-top-post">
    <div class="row top-post-detail">
        <a class="col-xs-4 col-sm-4 col-md-4 col-lg-4 top-post-img" href="{{ url_to_group($userJoinGroup) }}">
            <img class="border-radius-50-percent" src="{{ group_img_link($userJoinGroup, 'profile') }}">
        </a>
        <div class="col-xs-8 col-xs-8 col-sm-8 col-md-8 col-lg-8 top-post-content break-word">
            <a href="{{ url_to_group($userJoinGroup) }}" class="title" title="{{{ $userJoinGroup->name }}}">{{{ $userJoinGroup->name }}}</a>
            <a class="btn-post"
                href="{{ route('getGroupFillter',
                    [$userJoinGroup->shortname, \App\Services\GroupService::GROUP_FILTER_POST]) }}">
                {{ $postCount = $userJoinGroup->contentCount()->total_posts }}
                {{ \App\Services\HelperService::myPluralizer(trans('categories.post'), $postCount, $lang) }}
            </a>
            <a class="btn-post btn-user" href="javascript:void(0)"
                onclick="listAllUserJoinGroup(this)"
                data-id="{{ $userJoinGroup->id }}">
                {{ $userCount = $userJoinGroup->groupUsers()->count() }}
                {{ \App\Services\HelperService::myPluralizer(trans('users.user'), $userCount, $lang) }}
            </a>
        </div>
    </div>
</div>