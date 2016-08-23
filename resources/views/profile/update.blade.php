@extends('layouts.default')

@section('css')

{{ HTML::style(version('css_min/profile_update.min.css')) }}

@stop

@section('main')

<div class="col-md-12 setting">
    @include('layouts.includes.sidebar_setting')

    <div class="profile col-md-9 col-sm-8">
        <div class="panel panel-info">
            <div class="panel-heading">
                <div class="panel-title">{{ trans('messages.profile.update') }}</div>
            </div>
            <div class="panel-body" >
                <div class="col-md-12 col-sm-12 feedback">
                    {{ Form::open(['action' => 'ProfilesController@postUpdate', 'method' => 'POST', 'class' => 'form-horizontal form-update-profile', 'role' => 'form']) }}

                    @include('elements.message_notify', ['errors' => $errors])

                    <div class="form-group">
                        <div class="image  {{ (($lang == 'ja') ? 'col-sm-5 col-xs-5' : 'col-sm-3 col-xs-3') }}">
                            {{ Form::label('avatar', trans('messages.profile.avatar') . trans('messages.profile.change_avatar')) }}
                            <img class="fake-link" id="avatar-uploader" src="{{ $currentUser->getAvatar(60) }}" alt="{{ $currentUser->username }}" width="60" height="60">
                            @if ($currentUser->social_avatar_type || $currentUser->avatar)
                                <a class="btn btn-link" id="avatar-delete"
                                data-message="{{ ($currentUser->social_avatar_type) ? trans('messages.image.confirm_delete_social') :
                                                    trans('messages.image.confirm_delete', ['name' => $currentUser->avatar->originalName]) }}"
                                data-url="{{ ($currentUser->social_avatar_type) ? action('ImageController@postDestroy') :
                                                    action('ImageController@postDestroy', $currentUser->avatar_id) }}"
                                data-labels="{{ trans('messages.image.title_confirm') }}"
                                data-delete="{{ trans('buttons.confirm_delete') }}"
                                data-success="{{ trans('messages.image.success') }}"
                                data-false="{{ trans('messages.image.fail') }}"
                                data-ok="{{ trans('buttons.ok') }}">{{ trans('buttons.delete') }}</a>
                            @endif
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-4 col-xs-4">
                                {{ Form::label('name', trans('messages.profile.fullname')) }}
                                {{ Form::text('profile[first_name]', $profile->first_name, ['class' =>'form-control', 'maxlength' => 50, 'placeholder' => trans('messages.profile.firstname_placeholder')]) }}
                            </div>
                            <div class="col-sm-4 col-xs-4">
                                {{ Form::label('', '') }}
                                {{ Form::text('profile[last_name]', $profile->last_name, ['class' =>'form-control margin-sm-top', 'maxlength' => 50, 'placeholder' => trans('messages.profile.lastname_placeholder')]) }}
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-10 col-xs-10">
                                {{ Form::label('username', trans('messages.user.username')) }}
                                <span class='form-control' disabled>{{ $currentUser->username }}</span>
                            </div>
                            <div class="col-sm-2 col-xs-2">
                                {{ Form::label('', '') }}
                                {{ Form::select('setting[display_username_info]',
                                    [
                                        App\Services\SettingService::PUBLIC_PRIVACY => trans('messages.profile.public'),
                                        App\Services\SettingService::PRIVATE_PRIVACY => trans('messages.profile.private')
                                    ],
                                    $setting->display_username_info,
                                    ['class' => 'form-control margin-sm-top']
                                ) }}
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-10 col-xs-10">
                                {{ Form::label('email', trans('messages.user.email')) }}
                                {{ Form::text('user[email]', $currentUser->email, ['class' =>'form-control user-email', 'maxlength' => 255, 'required' => 'required', 'type' => 'email']) }}
                            </div>
                            <div class="col-sm-2 col-xs-2">
                                <span class='form-control display-status' disabled>{{ $setting->display_email ? trans('messages.profile.public') : trans('messages.profile.private') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-10 col-xs-10">
                                {{ Form::label('work_email', trans('messages.user.work_email')) }}
                                {{ Form::text('user[work_email]', $currentUser->work_email, ['class' =>'form-control user-work-email', 'maxlength' => 255]) }}
                            </div>
                            <div class="col-sm-2 col-xs-2">
                                <span class='form-control display-status' disabled>{{ $setting->display_work_email ? trans('messages.profile.public') : trans('messages.profile.private') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-10 col-xs-10">
                                {{ Form::label('phone', trans('messages.user.phone')) }}
                                {{ Form::text('user[phone]', $currentUser->phone, ['class' =>'form-control', 'maxlength' => 255, 'placeholder' => trans('messages.user.phone_placeholder')]) }}
                            </div>
                            <div class="col-sm-2 col-xs-2">
                                {{ Form::label('', '') }}
                                {{ Form::select('setting[display_phone_info]',
                                    [
                                        App\Services\SettingService::PUBLIC_PRIVACY => trans('messages.profile.public'),
                                        App\Services\SettingService::PRIVATE_PRIVACY => trans('messages.profile.private')
                                    ],
                                    $setting->display_phone_info,
                                    ['class' => 'form-control margin-sm-top']
                                ) }}
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-10 col-xs-10">
                                {{ Form::label('location', trans('messages.profile.location')) }}
                                {{ Form::select('profile[city_country_place_id]',
                                    [], null,
                                    [
                                        'class' =>'city-country form-control',
                                    ])
                                }}
                                {{ Form::hidden('profile[city_country_description]',
                                    isset($cityCountry) ? $cityCountry[$placeId] : null,
                                    ['class' => 'city-country-description'])
                                }}
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-10 col-xs-10">
                                {{ Form::text('profile[location]', $profile->location,
                                    [
                                        'class' =>'form-control location',
                                        'maxlength' => 255,
                                        'placeholder' => trans('messages.profile.location_placeholder'),
                                        'autocomplete' => 'off'
                                    ])
                                }}
                            </div>
                            <div class="col-sm-2 col-xs-2">
                                {{ Form::select('setting[display_location_info]',
                                    [
                                        App\Services\SettingService::PUBLIC_PRIVACY => trans('messages.profile.public'),
                                        App\Services\SettingService::PRIVATE_PRIVACY => trans('messages.profile.private')
                                    ],
                                    $setting->display_location_info,
                                    ['class' => 'form-control']
                                ) }}
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-10 col-xs-10">
                                {{ Form::label('url', trans('messages.profile.url')) }}
                                {{ Form::text('profile[url]', $profile->url, ['class' =>'form-control', 'maxlength' => 200, 'placeholder' => trans('messages.profile.url_placeholder')]) }}
                            </div>
                            <div class="col-sm-2 col-xs-2">
                                {{ Form::label('', '') }}
                                {{ Form::select('setting[display_url_info]',
                                    [
                                        App\Services\SettingService::PUBLIC_PRIVACY => trans('messages.profile.public'),
                                        App\Services\SettingService::PRIVATE_PRIVACY => trans('messages.profile.private')
                                    ],
                                    $setting->display_url_info,
                                    ['class' => 'form-control margin-sm-top']
                                ) }}
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-10 col-xs-10">
                                {{ Form::label('organization', trans('messages.profile.organization')) }}
                                {{ Form::text('profile[organization]', $profile->organization, ['class' =>'form-control', 'maxlength' => 255, 'placeholder' => trans('messages.profile.organization_placeholder')]) }}
                            </div>
                            <div class="col-sm-2 col-xs-2">
                                {{ Form::label('', '') }}
                                {{ Form::select('setting[display_organization_info]',
                                    [
                                        App\Services\SettingService::PUBLIC_PRIVACY => trans('messages.profile.public'),
                                        App\Services\SettingService::PRIVATE_PRIVACY => trans('messages.profile.private')
                                    ],
                                    $setting->display_organization_info,
                                    ['class' => 'form-control margin-sm-top']
                                ) }}
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-10 col-xs-10">
                                {{ Form::label('occupation', trans('messages.profile.occupation')) }}
                                {{ Form::text('profile[occupation]', $profile->occupation, ['class' =>'form-control', 'maxlength' => 255, 'placeholder' => trans('messages.profile.occupation_placeholder')]) }}
                            </div>
                            <div class="col-sm-2 col-xs-2">
                                {{ Form::label('', '') }}
                                {{ Form::select('setting[display_occupation_info]',
                                    [
                                        App\Services\SettingService::PUBLIC_PRIVACY => trans('messages.profile.public'),
                                        App\Services\SettingService::PRIVATE_PRIVACY => trans('messages.profile.private')
                                    ],
                                    $setting->display_occupation_info,
                                    ['class' => 'form-control margin-sm-top']
                                ) }}
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-10 col-xs-10">
                                {{ Form::label('description', trans('messages.profile.description')) }}
                                {{ Form::textarea('profile[description]', $profile->description, ['class' => 'form-control text-area', 'maxlength' => 500, 'placeholder' => trans('messages.profile.description_placeholder')]) }}
                            </div>
                            <div class="col-sm-2 col-xs-2">
                                {{ Form::label('', '') }}
                                {{ Form::select('setting[display_description_info]',
                                    [
                                        App\Services\SettingService::PUBLIC_PRIVACY => trans('messages.profile.public'),
                                        App\Services\SettingService::PRIVATE_PRIVACY => trans('messages.profile.private')
                                    ],
                                    $setting->display_description_info,
                                    ['class' => 'form-control margin-sm-top']
                                ) }}
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            {{ Form::label('social', trans('messages.setting.privacy_social_accounts'), ['class' => 'col-md-12']) }}
                        </div>
                    </div>

                    @foreach (\App\Services\SocialService::getFields() as $type => $social)
                        <div class="form-group">
                            <div class="col-sm-12 col-xs-12">
                                {{ Form::label($type, $social, ['class' => 'control-label']) }}
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                @if (\App\Services\SocialService::authorizedSocial($currentUser, $type))
                                    <div class="col-md-4">
                                        {{ trans('messages.profile.connected') }}
                                    </div>
                                    <div class="col-md-4">
                                        <a class="btn btn-sm btn-primary col-md-12"
                                            href="{{ URL::action('SocialsController@getRevoke', ['type' => $type]) }}"
                                            onclick="return confirm('{{ trans('socials.confirm_revoke') }}');">
                                            {{ trans('messages.profile.revoke') }}
                                        </a>
                                    </div>
                                @else
                                    <div class="col-md-4">
                                        {{ trans('messages.profile.disconnected') }}
                                    </div>
                                    <div class="col-md-4">
                                        <a class="btn btn-sm btn-success col-md-12"
                                            href="{{ URL::action('SocialsController@getAuthorize', ['type' => $type]) }}">
                                            {{ trans('messages.profile.connect') }}
                                        </a>
                                    </div>
                                @endif
                                <div class="col-md-4">
                                    <?php $socialAccount = 'display_' . $type . '_info' ?>
                                    {{ Form::select('setting[' . $socialAccount . ']',
                                        [
                                            App\Services\SettingService::PUBLIC_PRIVACY => trans('messages.profile.public'),
                                            App\Services\SettingService::PRIVATE_PRIVACY => trans('messages.profile.private')
                                        ],
                                        $setting->$socialAccount,
                                        ['class' => 'form-control']
                                    ) }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <div class="form-group pull-right">
                        {{ Form::submit(trans('buttons.update'),['class' => 'btn btn-success', 'name' => 'send'])  }}
                        <a class="btn btn-primary" href="{{ URL::previous() }}">{{ trans('buttons.cancel') }}</a>
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
</div>

<?php $skillObject = (object) ['name' => null, 'year' => null]; ?>

@stop
@section('script')
    <script type="text/javascript">
        var skills = {{ json_encode($suggestSkills) }};
        var maxSkillNumber = {{ App\Data\Blog\UserSkill::MAX_SKILL_NUMBER }};
        var aSkill = {{ json_encode(View::make('profile._a_skill', ['skill' => $skillObject, 'zeroOpacity' => true])->render()) }};
        var currentEmail = "{{ $currentEmail }}";
        var currentWorkEmail = "{{ $currentWorkEmail }}";
        var warningChangeEmail = "{{ trans('messages.profile.warning_change_email') }}";
        var warningChangeEmail1 = "{{ trans('messages.profile.warning_change_email_1') }}";
        var warningChangeWorkEmail = "{{ trans('messages.profile.warning_change_work_email') }}";
        var warningChangeWorkEmail1 = "{{ trans('messages.profile.warning_change_work_email_1') }}";
        var warningChangeBothEmails = "{{ trans('messages.profile.warning_change_both_emails') }}";
        var emptyPrivateWorkEmail = "{{ trans('messages.profile.empty_private_work_email') }}";
        var cityCountry = "{{ isset($cityCountry) ? $cityCountry[$placeId] : trans('labels.select_city') }}";
        var warningRemoveWorkEmail = "{{ trans('messages.profile.warning_remove_work_email') }}";
        var warningRemovePrivateEmail = "{{ trans('messages.profile.warning_remove_private_email') }}";
        var warningRemoveBothEmails = "{{ trans('messages.profile.warning_remove_both_emails') }}";
        var titleConfirm = "{{ trans('messages.profile.title_confirm') }}";
    </script>
    {{ HTML::script(version('js_min/profile_update.min.js'), ['defer' => 'defer']) }}
@stop
