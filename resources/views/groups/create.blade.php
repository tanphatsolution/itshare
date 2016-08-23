@extends('layouts.default')

@section('css')

{{ HTML::style(version('css_min/groups_create.min.css')) }}

@stop

@section('main')
<div class="post-detail all-post row">
    @if ($errors->has())
        <div class='alert alert-danger'>
            @foreach($errors->all() as $message)
                <p>{{ $message }}</p>
            @endforeach
        </div>
    @endif
    {{ Form::open(['action' => 'GroupsController@store', 'method' => 'POST','role' => 'form', 'files' => true, 'id' => 'create-group']) }}
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
            <input type="hidden" name="cover_img_crop_position" id="cover-img-crop-position" value="">
        </div>

        <span class="group-name">
            <input type="text" class="form-control input-group-name" autocomplete="off" name="name" placeholder="{{ trans('labels.groups.group_name') }}">
        </span><br/>
        <span class="group-name">
            <input type="text" class="form-control input-group-shortname" name="shortname" placeholder="{{ trans('labels.groups.group_shortname') }}">
        </span><br/>
        <span class="group-available alert alert-success hidden">
            {{ trans('messages.group.available') }}
        </span><br/>
        <span class="group-unavailable alert alert-warning hidden">
            {{ trans('messages.group.unavailable') }}
        </span><br/>

        <div class="col-md-9 col-lg-9 post-left">
            <div class="form privacy">
                <h3>{{ trans('labels.groups.privacy') }}</h3>
                {{ Form::radio('privacy_flag', \App\Data\Blog\GroupSetting::PRIVACY_PUBLIC, true) }}
                    <span>{{ trans('labels.groups.privacy_public') }}</span>
                <br>
                {{ Form::radio('privacy_flag', \App\Data\Blog\GroupSetting::PRIVACY_PROTECTED, false) }}
                    <span>{{ trans('labels.groups.privacy_protected') }}</span>
                <br>
                {{ Form::radio('privacy_flag', \App\Data\Blog\GroupSetting::PRIVACY_PRIVATE, false) }}
                    <span>{{ trans('labels.groups.privacy_private') }}</span>
            </div>
            <div class="form confirm-member">
                <h3>{{ trans('labels.groups.add_member') }}</h3>
                {{ Form::radio('add_member_flag', \App\Data\Blog\GroupSetting::ALL_CAN_ADD_MEMBER, true) }}
                    <span>{{ trans('labels.groups.add_member_all') }}</span>
                <br>
                {{ Form::radio('add_member_flag', \App\Data\Blog\GroupSetting::ALL_CAN_ADD_MEMBER_WITH_PERMISSION, false) }}
                    <span>{{ trans('labels.groups.add_member_all_with_permission') }}</span>
                <br>
                {{ Form::radio('add_member_flag', \App\Data\Blog\GroupSetting::ONLY_ADMIN_CAN_ADD_MEMBER, false) }}
                    <span>{{ trans('labels.groups.add_member_only_admin') }}</span>
            </div>
            <div class="form jurisdiction">
                <h3>{{ trans('labels.groups.post_to_group') }}</h3>
                {{ Form::radio('add_post_flag', \App\Data\Blog\GroupSetting::ALL_CAN_POST, true) }}
                    <span>{{ trans('labels.groups.post_to_group_all') }}</span>
                <br>
                {{ Form::radio('add_post_flag', \App\Data\Blog\GroupSetting::ALL_CAN_POST_WITH_PERMISSION, false) }}
                    <span>{{ trans('labels.groups.post_to_group_all_with_permission') }}</span>
                <br>
                {{ Form::radio('add_post_flag', \App\Data\Blog\GroupSetting::ONLY_ADMIN_CAN_POST, false) }}
                    <span>{{ trans('labels.groups.post_to_group_only_admin') }}</span>
                </div>
            <div class="form comfirm-admin">
                <h3>{{ trans('labels.groups.edit_post') }}</h3>
                {{ Form::radio('edit_post_flag', \App\Data\Blog\GroupSetting::ALL_CAN_EDIT_POST, true) }}
                    <span>{{ trans('labels.groups.edit_post_all') }}</span>
                <br>
                {{ Form::radio('edit_post_flag', \App\Data\Blog\GroupSetting::ONLY_ADMIN_CAN_EDIT_POST, false) }}
                    <span>{{ trans('labels.groups.edit_post_only_admin') }}</span>
                <br>
                {{ Form::radio('edit_post_flag', \App\Data\Blog\GroupSetting::ONLY_AUTHOR_CAN_EDIT_POST, false) }}
                    <span>{{ trans('labels.groups.edit_post_only_author') }}</span>
                <br>
            </div>
            <div class="form comfirm-admin">
                <h3>{{ trans('labels.groups.edit_series') }}</h3>
                {{ Form::radio('edit_series_flag', \App\Data\Blog\GroupSetting::ALL_CAN_EDIT_SERIES, true) }}
                    <span>{{ trans('labels.groups.edit_series_all') }}</span>
                <br>
                {{ Form::radio('edit_series_flag', \App\Data\Blog\GroupSetting::ONLY_ADMIN_AUTHOR_CAN_EDIT_SERIES, false) }}
                    <span>{{ trans('labels.groups.edit_series_only_admin_author') }}</span>
                <br>
                {{ Form::radio('edit_series_flag', \App\Data\Blog\GroupSetting::ONLY_AUTHOR_CAN_EDIT_SERIES, false) }}
                    <span>{{ trans('labels.groups.edit_series_only_author') }}</span>
                <br>
            </div>
            <div class="form submit">
                {{ Form::submit(trans('buttons.create'), ['class' => 'save', 'id' => 'submit-create-group-form']) }}
            </div>
        </div>
        <div class="col-md-3 col-lg-3 post-right">
            <div class="img-viblo edit-picture" id="group-profile-image-cropper">
                <div class="cropit-image-preview l-pic"></div>

                <div class='update-pic profile-image-upload {{ isset($lang) ? "update-pic-$lang" : "" }}'  id="choice-group-icon">
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
                <input type="hidden" name="profile_img_crop_position" id="profile-img-crop-position" value="">
            </div>
            <p class="link-viblo">
                <input type="text" class="form-control" name="url" placeholder="{{ trans('labels.groups.url') }}">
            </p>
            <p class="link-content">
                <textarea class="form-control" name="description" placeholder="{{ trans('labels.groups.summary') }}" rows="8"></textarea>
            </p>
            <div class="box-top-post">
                <p class="title-right">{{ trans('labels.groups.member') }}</p>
                <div class="list-top-post">
                    <div class="add-member-notice"></div>
                    <div class="addmember">
                        {{ Form::text('add_member', null,
                            ['placeholder' => trans('labels.groups.add_member'), 'class' => 'add-member', 'id' => 'add-member']) }}
                    </div>
                    <ul class="member-admin">
                    @if (isset($userMembers))
                        @foreach ($userMembers as $member)
                            @if (Auth::user()->id != $member->user->id)
                                @include('groups._group_create_a_member', ['user' => $member->user])
                            @endif
                        @endforeach
                    @endif
                    </ul>
                </div>
            </div>
        </div>
    {{ Form::close() }}
</div>
@stop

@section('script')

    <script type="text/javascript">
        var coverImage = "{{ asset(Config::get('image.group_image.group_cover_default')) }}";
        var profileImage = "{{ asset(Config::get('image.group_image.profile_no_image')) }}";
        var imgMaxSize = {{ Config::get('image')['max_image_size'] }};
    </script>
    {{ HTML::script(version('js_min/groups_create.min.js')) }}
@stop
