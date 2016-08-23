@extends('layouts.default')

@section('css')

{{ HTML::style(version('css_min/groups_edit.min.css')) }}

@stop

@section('main')

<div class="post-detail all-post row">
    @include('elements.message_notify', ['errors' => $errors])

    {{ Form::open(['action' => ['GroupsController@update', $group->encrypted_id],
        'method' => 'PATCH', 'role' => 'form', 'files' => true, 'id' => 'edit-group']) }}
        {{ Form::hidden('encryptedId', $group->encrypted_id) }}
        <div class="images-group edit-picture" id="group-cover-image-cropper">
            <div class="cropit-image-preview"></div>

            <div class='update-pic cover-image-upload {{ isset($lang) ? "update-pic-$lang" : "" }}' id="choice-image-header">
                <div class="edit-pic cover-image-upload"></div>
                <span class="up-pic cover-image-upload">{{ trans('buttons.upload_picture') }}</span>
            </div>

            <div class="choice-pic img-action">
                <button class="save-cover-image">{{ trans('buttons.save') }}</button>
                <button class="cancel-cover-image">{{ trans('buttons.cancel') }}</button>
            </div>

            <div class="slider-wrapper">
                <span class="glyphicon glyphicon-picture small-glyphicon"></span>
                <input type="range" class="cropit-image-zoom-input custom" min="0" max="1" step="0.01">
                <span class="glyphicon glyphicon-picture large-glyphicon"></span>
            </div>


            <input type="file" accept="image/*" name="cover_img" id="cover-img" class="image-upload cropit-image-input" />
            <input type="hidden" name="cover_img_crop_position" id="cover-img-crop-position" value="{{{ $group->cover_img_crop_position }}}">
        </div>

        <span class="group-name break-word">{{ trans('labels.groups.label_group_name') }}
            <a href="javascript:void(0)" id="group-name"
                data-type="text" data-placement="right"
                data-title="{{ trans('labels.groups.group_name') }}">{{{ $group['name'] }}}<img src="/img/icon-edit-group.png" alt="edit"></a>
            <input type="hidden" id="hidden-groupname" name="name" value="{{{ $group['name'] }}}">
            <div class="editable-error-block help-block editable-groupname has-error">
                <label class="control-label"></label>
            </div>
        </span><br>

        <span class="group-name break-word">{{ trans('labels.groups.label_group_shortname') }}
            <a href="javascript:void(0)" id="group-shortname"
                data-type="text" data-placement="right"
                data-title="{{ trans('labels.groups.label_group_shortname') }}">
                @if ($group['is_shortname'])
                    {{{ $group['shortname'] }}}
                @endif
                <img src="/img/icon-edit-group.png" alt="edit" /></a>
            <input type="hidden" id="hidden-group-is-shortname" name="is_shortname" value="{{{ $group['is_shortname'] }}}" />
            <input type="hidden" id="hidden-group-shortname" name="shortname" value="{{{ $group['shortname'] }}}">
            <div class="editable-error-block help-block editable-shortname has-error">
                <label class="control-label"></label>
            </div>
        </span><br>

        <div class="col-md-9 col-lg-9 post-left">
            <div class="form privacy">
                <h3>{{ trans('labels.groups.privacy') }}</h3>
                {{ Form::radio('privacy_flag', App\Data\Blog\GroupSetting::PRIVACY_PUBLIC,
                    isset($groupSetting->privacy_flag) && $groupSetting->privacy_flag == App\Data\Blog\GroupSetting::PRIVACY_PUBLIC ? true : false) }}
                    <span>{{ trans('labels.groups.privacy_public') }}</span>
                <br>
                {{ Form::radio('privacy_flag', App\Data\Blog\GroupSetting::PRIVACY_PROTECTED,
                    isset($groupSetting->privacy_flag) && $groupSetting->privacy_flag == App\Data\Blog\GroupSetting::PRIVACY_PROTECTED ? true : false) }}

                    <span>{{ trans('labels.groups.privacy_protected') }}</span>
                <br>
                {{ Form::radio('privacy_flag', App\Data\Blog\GroupSetting::PRIVACY_PRIVATE,
                    isset($groupSetting->privacy_flag) && $groupSetting->privacy_flag == App\Data\Blog\GroupSetting::PRIVACY_PRIVATE ? true : false) }}
                    <span>{{ trans('labels.groups.privacy_private') }}</span>
            </div>
            <div class="form confirm-member">
                <h3>{{ trans('labels.groups.add_member') }}</h3>
                {{ Form::radio('add_member_flag', App\Data\Blog\GroupSetting::ALL_CAN_ADD_MEMBER,
                    isset($groupSetting->add_member_flag) && $groupSetting->add_member_flag == App\Data\Blog\GroupSetting::ALL_CAN_ADD_MEMBER ? true : false) }}
                    <span>{{ trans('labels.groups.add_member_all') }}</span>
                <br>
                {{ Form::radio('add_member_flag', App\Data\Blog\GroupSetting::ALL_CAN_ADD_MEMBER_WITH_PERMISSION,
                    isset($groupSetting->add_member_flag) && $groupSetting->add_member_flag == App\Data\Blog\GroupSetting::ALL_CAN_ADD_MEMBER_WITH_PERMISSION ? true : false) }}
                    <span>{{ trans('labels.groups.add_member_all_with_permission') }}</span>
                <br>
                {{ Form::radio('add_member_flag', App\Data\Blog\GroupSetting::ONLY_ADMIN_CAN_ADD_MEMBER,
                    isset($groupSetting->add_member_flag) && $groupSetting->add_member_flag == App\Data\Blog\GroupSetting::ONLY_ADMIN_CAN_ADD_MEMBER ? true : false) }}
                    <span>{{ trans('labels.groups.add_member_only_admin') }}</span>
            </div>
            <div class="form jurisdiction">
                <h3>{{ trans('labels.groups.post_to_group') }}</h3>
                {{ Form::radio('add_post_flag', App\Data\Blog\GroupSetting::ALL_CAN_POST,
                    
                    ( (isset($groupSetting->add_post_flag) && isset($groupSetting->approve_post_flag)) &&
                      ($groupSetting->add_post_flag == App\Data\Blog\GroupSetting::ALL_CAN_POST) &&
                      ($groupSetting->approve_post_flag == App\Data\Blog\GroupSetting::POST_NO_NEED_APPROVE)
                    ) ? true : false) }}
                    <span>{{ trans('labels.groups.post_to_group_all') }}</span>
                <br>
                {{ Form::radio('add_post_flag', App\Data\Blog\GroupSetting::ALL_CAN_POST_WITH_PERMISSION,
                    ( isset($groupSetting->add_post_flag) && ($groupSetting->add_post_flag == App\Data\Blog\GroupSetting::ALL_CAN_POST) &&
                    ($groupSetting->approve_post_flag == App\Data\Blog\GroupSetting::POST_NEED_APPROVE))
                    ? true : false) }}
                    <span>{{ trans('labels.groups.post_to_group_all_with_permission') }}</span>
                <br>
                {{ Form::radio('add_post_flag', App\Data\Blog\GroupSetting::ONLY_ADMIN_CAN_POST,
                    isset($groupSetting->add_post_flag) && $groupSetting->add_post_flag == App\Data\Blog\GroupSetting::ONLY_ADMIN_CAN_POST ? true : false) }}
                    <span>{{ trans('labels.groups.post_to_group_only_admin') }}</span>
            </div>

            <div class="form comfirm-admin">
                <h3>{{ trans('labels.groups.edit_post') }}</h3>
                {{ Form::radio('edit_post_flag', App\Data\Blog\GroupSetting::ALL_CAN_EDIT_POST,
                    isset($groupSetting->edit_post_flag) && $groupSetting->edit_post_flag == \App\Data\Blog\GroupSetting::ALL_CAN_EDIT_POST ? true : false) }}
                    <span>{{ trans('labels.groups.edit_post_all') }}</span>
                <br>
                {{ Form::radio('edit_post_flag', App\Data\Blog\GroupSetting::ONLY_ADMIN_CAN_EDIT_POST,
                    isset($groupSetting->edit_post_flag) && $groupSetting->edit_post_flag == App\Data\Blog\GroupSetting::ONLY_ADMIN_CAN_EDIT_POST ? true : false) }}
                    <span>{{ trans('labels.groups.edit_post_only_admin') }}</span>
                <br>
                {{ Form::radio('edit_post_flag', App\Data\Blog\GroupSetting::ONLY_AUTHOR_CAN_EDIT_POST,
                    isset($groupSetting->edit_post_flag) && $groupSetting->edit_post_flag == App\Data\Blog\GroupSetting::ONLY_AUTHOR_CAN_EDIT_POST ? true : false) }}
                    <span>{{ trans('labels.groups.edit_post_only_author') }}</span>
                <br>
            </div>

            <div class="form comfirm-admin">
                <h3>{{ trans('labels.groups.edit_series') }}</h3>
                {{ Form::radio('edit_series_flag', App\Data\Blog\GroupSetting::ALL_CAN_EDIT_SERIES,
                    isset($groupSetting->edit_series_flag) && $groupSetting->edit_series_flag == App\Data\Blog\GroupSetting::ALL_CAN_EDIT_SERIES ? true : false) }}
                    <span>{{ trans('labels.groups.edit_series_all') }}</span>
                <br>
                {{ Form::radio('edit_series_flag', App\Data\Blog\GroupSetting::ONLY_ADMIN_AUTHOR_CAN_EDIT_SERIES,
                    isset($groupSetting->edit_series_flag) && $groupSetting->edit_series_flag == App\Data\Blog\GroupSetting::ONLY_ADMIN_AUTHOR_CAN_EDIT_SERIES ? true : false) }}
                    <span>{{ trans('labels.groups.edit_series_only_admin_author') }}</span>
                <br>
                {{ Form::radio('edit_series_flag', App\Data\Blog\GroupSetting::ONLY_AUTHOR_CAN_EDIT_SERIES,
                    isset($groupSetting->edit_series_flag) && $groupSetting->edit_series_flag == App\Data\Blog\GroupSetting::ONLY_AUTHOR_CAN_EDIT_SERIES ? true : false) }}
                    <span>{{ trans('labels.groups.edit_series_only_author') }}</span>
                <br>
            </div>
            <div class="form submit">
                {{ Form::submit(trans('buttons.save'), ['class' => 'save', 'id' => 'submit-edit-group-form']) }}
            </div>
        </div>

        <div class="col-md-3 col-lg-3 post-right">
            <div class="img-viblo edit-picture" id="group-profile-image-cropper">
                <div class="cropit-image-preview l-pic"></div>

                <div class='update-pic profile-image-upload {{ isset($lang) ? "update-pic-$lang" : "" }}' id="choice-group-icon">
                    <div class="edit-pic profile-image-upload"></div>
                    <span class="up-pic profile-image-upload">{{ trans('buttons.upload_picture') }}</span>
                </div>

                <div class="choice-pic img-action">
                    <button class="save-profile-image">{{ trans('buttons.save') }}</button>
                    <button class="cancel-profile-image">{{ trans('buttons.cancel') }}</button>
                </div>

                <div class="slider-wrapper">
                    <span class="glyphicon glyphicon-picture small-glyphicon"></span>
                    <input type="range" class="cropit-image-zoom-input custom" min="0" max="1" step="0.01">
                    <span class="glyphicon glyphicon-picture large-glyphicon"></span>
                </div>

                <input type="file" accept="image/*" name="profile_img" id="profile-img" class="image-upload cropit-image-input" />
                <input type="hidden" name="profile_img_crop_position" id="profile-img-crop-position" value="{{{ $group->profile_img_crop_position }}}">
            </div>

            <p class="link-viblo break-word">
                <a href="javascript:void(0)" id="url" data-type="text" data-placement="right"
                    data-title="{{ trans('labels.groups.url') }}" data-value="{{{ $group['url'] }}}" >
                    {{{ str_limit($group['url'], $limit = 70, $end = '...') }}}
                    <img src="/img/icon-edit-group.png" alt="edit">
                </a>
            </p>
            <ul class="row ul-none-padding">
                <?php $groupName = mb_strlen($group['name']) >= 16 ? mb_substr($group['name'], 0, 16) . '...' : $group['name'] ?>
                <li class="delete_group full_width" data-group="{{ $groupName }}">
                    {{ trans('labels.groups.delete_group') }}
                </li>
            </ul>
            <p class="link-content break-word no-cursor edit-group-description">
                <a href="javascript:void(0)" id="description" data-type="textarea"
                    data-placement="right" data-title="{{ trans('labels.groups.summary') }}">{{{ trim($group['description']) }}}<img src="{{ asset('img/icon-edit-group.png') }}" alt="edit">
                </a>
            </p>
            <div class="box-top-post">
                <p class="title-right">{{ trans('labels.groups.member') }}</p>
                <div class="list-top-post">
                    <div class="add-member-notice"></div>
                    <div class="addmember">
                        {{ Form::text('add_member', null,
                            ['placeholder' => trans('labels.groups.add_member'), 'class' => 'add-member', 'id' => 'add-member']) }}
                    </div>
                    <div class="group-list-members">
                        @include('groups._group_list_members', ['userMembers' => $userMembers])
                    </div>
                </div>
            </div>
        </div>
    {{ Form::close() }}
</div>

@stop

@section('script')

    <script type="text/javascript">
        var coverImage = "{{ asset(is_null($group->cover_img) || empty($group->cover_img) ? Config::get('image.group_image.group_cover_default') :  $group->cover_img) }}";
        var profileImage = "{{ asset(is_null($group->profile_img) || empty($group->profile_img) ? Config::get('image.group_image.profile_no_image') :  $group->profile_img) }}";
        var defaultCoverImage = "{{ asset(Config::get('image.group_image.group_cover_default')) }}";
        var defaultProfileImage = "{{ asset(Config::get('image.group_image.profile_no_image')) }}";
        var imgMaxSize = {{ Config::get('image')['max_image_size'] }};
        var groupId = {{ $group->id }};
        var blankImg = "{{ asset('img/blank.png') }}";
        var delete_group_message = "{{ $privacyGroup }}";
        var groupEncryptedId = '{{ $group->encryptedId }}';
        var emptyField = "{{ trans('labels.empty_filed') }}";
        var deletedLabel = "{{ trans('labels.deleted') }}";
        var addSuccessMsg = "{{ trans('messages.group.add_member_success') }}";
        var addFailMsg = "{{ trans('messages.group.add_member_fail') }}";
        var confirmRemove = "{{ trans('messages.group.confirm_remove_member') }}";
        var confirmChangeRole = "{{ trans('messages.group.confirm_change_role') }}";
        var cannotRemoveMsg = "{{ trans('messages.group.can_not_remove') }}";
        var cannotChangeRoleMsg = "{{ trans('messages.group.can_not_change_role') }}";
        var removeSusscessMsg = "{{ trans('messages.group.remove_member_success') }}";
        var changeRoleSuccessMsg = "{{ trans('messages.group.change_role_success') }}";
    </script>

    {{ HTML::script(version('js_min/groups_edit.min.js')) }}
@stop
