@foreach ($groups as $group)
    <div class="group-top col-lg-12">
        <div class="box-top-group row">
            <div class="boxgroup-top col-lg-2 col-md-2 col-sm-2 col-xs-2 col_width_2">
                <a href="{{ url_to_group($group) }}">
                    <img src="{{ config('image.base64') }}" class="lazy" data-original = "{{group_img_link($group, 'profile')}}">
                </a>
            </div>
            <div class="right-box col-lg-10 col-md-10 col-sm-10 col-xs-10 col_width_4">
                <div class="clearfix">
                    <div class="caption-name col-lg-9 col-md-9 col-sm-9">
                        <div class="group-name">
                            <a class="name-group break-word" title="{{{ $group->name }}}" href="{{ url_to_group($group) }}">
                                {{{ $group->name }}}
                            </a>
                        </div>
                    </div>
                    <div class="join-group col-lg-3 col-md-3 col-sm-3">
                        <?php $groupUserService = $group->current_user_group ?>
                        @if (!is_null($groupUserService))
                            @if ($groupUserService->isWaiting())
                                <button type="button" href="javascript:void(0)" data-id="{{ $group->id }}" data-flag="0" class="btn-join-group">
                                    {{ trans('labels.groups.undo_request') }}
                                </button><br>
                            @endif
                        @elseif (Auth::check())
                            <button type="button" href="javascript:void(0)" data-id="{{ $group->id }}" data-flag="1" class="btn-join-group">
                                {{ trans('labels.groups.join_group') }}
                            </button><br>
                        @endif
                    </div>
                </div>
                <div class="clearfix">
                    <div class="caption-name col-lg-2 col-md-2 col-sm-2 col_width_1">
                        <?php
                             $groupUsers = $group->groupUsers();
                             $contentCount = $group->contentCount();
                        ?>
                        @if (!empty($groupUsers))
                            <ul class="mini-post">
                                <li class="post-view" title="Total posts">{{ $contentCount->total_posts }}</li>
                                <li class="post-com" title="Total series">{{ $contentCount->total_series }}</li>
                                <li class="post-favou" title="Total users">{{ $groupUsers->count() }}</li>
                            </ul>
                        @endif
                    </div>
                    <div class="short-description col-lg-5 col-md-5 col-sm-5 col_width_3">
                        {{ \App\Services\HelperService::getStripTagGroupDescription($group->description) }}
                    </div>
                    <div class="join-group col-lg-5 col-md-5 col-sm-5">
                        @include('groups._list_user_join_group')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endforeach