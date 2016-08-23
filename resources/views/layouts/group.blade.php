@extends('layouts.default')

@section('css')
    {{ HTML::style(version('css_min/layouts_group.min.css')) }}
    @yield('group-css')
@stop

@section('main')
    <div class="group-area">
        <div class="images-group" id="group-cover-image-cropper">
            <div class="cropit-image-preview"></div>
            <input type="hidden" name="cover_img_crop_position" id="cover-img-crop-position" value="{{{ $group->cover_img_crop_position }}}">
        </div>

        <div class="col-lg-12 l-box-name">
            <span class="l-setting">{{ $privacy }}</span>
            <a href="{{ url_to_group($group) }}"><span class="group-name padding-float-left">
                @if (mb_strlen($group->name) >= 55)
                    {{{ mb_substr($group->name, 0, 55) . '...' }}}
                @else
                    {{{ $group->name }}}
                @endif
            </span></a>
        </div>
        <div class="col-sm-12 col-md-9 col-lg-9 post-left group-detail">
           @yield('group-main')
        </div>

        <div class="col-sm-12 col-md-3 col-lg-3 post-right no-padding-right">
            <div class="img-viblo" id="group-profile-image-cropper">
                <div class="cropit-image-preview l-pic"></div>

                <input type="hidden" name="profile_img_crop_position" id="profile-img-crop-position" value="{{{ $group->profile_img_crop_position }}}">
            </div>

            <ul>
                @if (!is_null($groupUser))
                    @if ($groupUser->isMember())
                        <li class="leavegroup btn-leave-group full_width">{{ trans('labels.groups.leave_button') }}</li>
                    @elseif ($groupUser->isWaiting())
                        <li class="leavegroup btn-join-group full_width">{{ trans('labels.groups.undo_request') }}</li>
                    @endif
                @elseif (Auth::check())
                    <li class="leavegroup btn-join-group request-join-group full_width">{{ trans('labels.groups.join_group') }}</li>
                @endif
            </ul>

            @if (!empty($group->url))
                <p class="link-viblo break-word">
                    {{  HTML::link($group->url, str_limit($group['url'], $limit = 70, $end = '...'), ['target' => '_blank']) }}
                </p>
            @endif
            @if (isset($groupDescription))
                <div class="link-content break-word no-cursor process-html-content">{{ $groupDescription }}</div>
            @endif
            <div class="input-group">
                {{ Form::open(['action' => ['GroupsController@search', $group->encrypted_id],
                    'method' => 'POST', 'role' => 'form', 'id' => 'search-group'])
                }}
                    <span class="input-group-btn"></span>
                    {{ Form::text(
                            'keywords',
                            null,
                            ['class' => 'form-control', 'placeholder' => trans('labels.groups.search'), 'id' => 'key-words'])
                    }}
                {{ Form::close() }}
            </div>

            @if (!empty($groupUser) && $groupUser->isAdmin())
                <div class="screen-admin">
                    <a href="{{ URL::action('GroupsController@edit', [$group->shortname]) }}">{{ trans('labels.groups.edit_this_group') }}</a>
                </div>
            @endif

            <div class="box-top-post top-author group-members">
                <p class="title-right group-header-title">{{ trans('labels.groups.members') }}</p>
                <?php $noPaddingTop = (!empty($groupUser) && ($groupUser->isAdmin() || ($groupUser->isMember() && $groupSettings->isMemberCanAddMember()))) ?>
                <div class="list-top-post group-member-list-area {{ $noPaddingTop ? '' : 'no-padding-top' }}">
                    @if (!empty($groupUser) && ($groupUser->isAdmin() || ($groupUser->isMember() && $groupSettings->isMemberCanAddMember())))
                        <div class="add-member-notice"></div>
                        <div class="addmember">
                            {{ Form::text('add_member', null, ['placeholder' => trans('labels.groups.add_member'), 'class' => 'add-member', 'id' => 'add-member']) }}
                        </div>
                    @endif

                    <ul class="member group-list-members">
                        @foreach ($userMembers as $member)
                            @include('groups._a_member_detail', ['member' => $member])
                        @endforeach
                    </ul>

                    <button href="javascript:void(0)" class=" viewall show-group-members">{{ trans('labels.groups.all_member') }}</button>
                    <div id="modal-list-group-users"></div>

                    @if (!empty($groupUser) && $groupUser->isMember())
                        <div class="creatgroup"><a href="{{ route('getGroupEncryptCreate', [$group->encrypted_id]) }}">{{ trans('labels.groups.create_group_with_members') }}</a></div>
                    @endif
                </div>
            </div>

            @if (!empty($groupUser) && $groupUser->isAdmin() && !($unapprovedPosts->isEmpty()))
                <div class="box-top-post top-author group-unapproved-posts">
                    <p class="title-right">{{ trans('labels.groups.unapproved_post') }}</p>

                    <ul class="post-view group-unapproved-list break-word">
                        @include('groups._a_unapproved_post', [
                            'unapprovedPosts' => $unapprovedPosts,
                            'group' => $group,
                            'isAdmin' => !empty($groupUser) && $groupUser->isAdmin()
                        ])
                    </ul>
                </div>
            @endif

            @if (!empty($groupUser) && $groupUser->isAdmin() && !($unapprovedUsers->isEmpty()))
                <div class="box-top-post top-author group-unapproved-users">
                    <p class="title-right group-header-title">{{ trans('labels.groups.unapproved_user') }}</p>

                    <div class="group-unapproved-users-list">
                        @include('groups._a_unapproved_user', [
                            'unapprovedUsers' => $unapprovedUsers,
                            'group' => $group
                        ])
                    </div>
                </div>
            @endif

            <div class="box-top-post top-author group-categories">
                <p class="title-right group-header-title">{{ trans('labels.groups.categories') }}</p>
                <div class="group-categories-list">
                    <ul class="category">
                        @foreach ($groupCategories as $singleCategory)
                            <li>
                                <a href="{{ url_to_group_category($group->encrypted_id, $singleCategory->category) }}">
                                    {{{ $singleCategory->category->name }}}
                                </a>
                                <span class="sum-article">{{ $singleCategory->toArray()['postsNumber'] }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        <div class="clearfix"></div>
    </div>
@stop

@section('script')
    <script type="text/javascript">
        var groupId = "{{ $group->id }}",
            coverImage = "{{ asset(is_null($group->cover_img) || empty($group->cover_img) ? Config::get('image.group_image.group_cover_default') :  $group->cover_img) }}",
            profileImage = "{{ asset(is_null($group->profile_img) || empty($group->profile_img) ? Config::get('image.group_image.profile_no_image') :  $group->profile_img) }}",
            blankImg = "{{ asset('img/blank.png') }}",
            groupDetailPageFlag = true;
        var addSuccessMsg = "{{ trans('messages.group.add_member_success') }}";
        var addFailMsg = "{{ trans('messages.group.add_member_fail') }}";
        var noticeLinkSeries = "{{ trans('messages.group.notice_link_series') }}";
    </script>
    {{ HTML::script(version('js_min/layouts_group.min.js'), ['defer'=>'defer']) }}
    @yield('group-script')
@stop
