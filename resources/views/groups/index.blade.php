@extends('layouts.default')

@section('css')
    {{ HTML::style(version('css_min/groups_index.min.css')) }}
@stop

@section('main')
    <div class="post-detail all-post">
        <div class="col-md-9 col-lg-9 post-left top-group">
            <div class="box-title box-title-child">
                <ul class="list-title">
                    <li>
                        <a class="{{ !isset($follow) ? 'selected' : '' }}" href="{{ URL::to('groups') }}">
                            {{ trans('labels.groups.group_list') }}
                        </a>
                    </li>
                    @if ($currentUser)
                        <li>
                            <a class="{{ isset($follow) ? 'selected' : '' }}" href="{{ URL::to('groups/follow') }}">
                                {{ trans('labels.groups.follow_user') }}
                            </a>
                        </li>
                    @endif
                </ul>
                <div class="addgroup"><a href="{{ route('groups.create') }}">{{ trans('labels.groups.create_group') }}</a></div>
                <div class="clear-both"></div>
            </div>
            <div class="group-post" id="list_groups">
                @include('groups._list_groups', ['groups' => $groups])

                <div class="clr"></div>
            </div>
            <div class="load-more">
                @if ($seeMore > 0)
                    <a id="loadMoreGroups" data-value="{{ isset($follow) && $follow ? $follow : 0 }}" href="javascript:void(0)">
                        {{ trans('labels.load_more') }}
                    </a>
                @endif
            </div>
        </div>

        <div class="col-md-3 col-lg-3 post-right post-group-top">
            <div class="box-top-posts top-author">
                <p class="title-right">{{ trans('labels.groups.joined_group') }}</p>
                <div class="joined-groups">
                    @if (!is_null($userJoinedGroups))
                        @include('groups._user_joined_groups', ['userJoinedGroups' => $userJoinedGroups, 'lang' => $lang])
                    @endif
                </div>
                @if ($seeMoreUserGroup)
                <div class="load-more more-user-groups">
                    <a id="load-more-user-groups" href="javascript:void(0)">
                        {{ trans('labels.more') }}
                    </a>
                </div>
                @endif
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
    <div id="modal-list-group-users"></div>
@stop

@section('script')
    {{ HTML::script(version('js_min/groups_index.min.js'), ['async' => 'async']) }}
@stop
