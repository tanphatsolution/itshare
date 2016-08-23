@foreach ($unapprovedUsers as $unaprrovedUser)
    <div class="unapproved-user">
        <div class="unapproved-user-info col-sm-4 col-md-4 col-lg-4">
            <a href="{{ url_to_user($unaprrovedUser->user) }}" title="{{ $unaprrovedUser->user->name }}">
               <img src="{{ asset('img/blank.png') }}"
                style="background-image: url({{ user_img_url($unaprrovedUser->user, 300) }})"
                class="avatar-image">
           </a>
        </div>

        <div class="unapproved-user-action col-sm-8 col-md-8 col-lg-8">
            <a href="javascript:void(0)" class="dic approve-group-user"
                data-group-id="{{ $group->id }}" data-user-id="{{ $unaprrovedUser->user_id }}"
                onclick="approveGroupUser(this, 0)">
                {{ trans('labels.groups.deny') }}
            </a>

            <a href="javascript:void(0)" class="sub approve-group-user"
                data-group-id="{{ $group->id }}" data-user-id="{{ $unaprrovedUser->user_id }}"
                onclick="approveGroupUser(this, 1)">
                {{ trans('labels.groups.approve') }}
            </a>
        </div>
        <div class="clr"></div>
    </div>
@endforeach

