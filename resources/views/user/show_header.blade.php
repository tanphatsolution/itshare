<div class="container box-profile">
    <div class="row ">
        <a href="{{ url_to_user($user) }}">
            <img class="col-md-2 col-lg-2 avar-profile" src="{{ asset('img/blank.png') }}" style="background: url({{ user_img_url($user, 300) }})">
        </a>
        <div class="col-md-6 col-lg-6 content-profile">
            <p class="name-profile">{{{  get_full_name_of_user($user) }}}</p>
            <div class="social-profile">
                @if (!empty($socialList))
                    @foreach (\App\Services\SocialService::getFields() as $social => $field)
                        @if (\App\Services\HelperService::showAll($setting, $social) && isset($socialList[$social]))
                            <a href="{{ $socialList[$social] }}" target="_blank" data-toggle="tooltip"
                                data-placement="bottom"
                                title="{{ trans('labels.go_to_social', [
                                    'username' => $user->username,
                                    'social' => $field
                                ]) }}">
                                <img class="{{ $social }}" src="{{ asset('img/blank.png') }}">
                            </a>
                        @endif
                    @endforeach
                @endif

                @include('relationships.relationships', ['currentUser' => $currentUser, 'user' => $user, 'class' => 'btn-follow-mini'])

                <div class="clear-both"></div>
            </div>
            <p class="position break-word">
                @if (\App\Services\HelperService::showAttribute($setting, $profile, 'occupation'))
                    {{ HTML::entities($profile->occupation) }}
                @endif
                @if (\App\Services\HelperService::showAttribute($setting, $profile, 'organization'))
                     {{ HTML::entities($profile->organization) }}
                @endif
            </p>

            @if (\App\Services\HelperService::showAttribute($setting, $profile, 'description'))
                <p class="description break-word">{{ HTML::entities($profile->description) }}</p>
            @endif
            @if (\App\Services\HelperService::showAttribute($setting, $user, 'phone'))
                <p class="phone">{{ HTML::entities($user->phone) }}</p>
            @endif
            @if (\App\Services\HelperService::showAttribute($setting, $profile, 'url'))
                <a class="link-website" target="_blank" href="{{ $profile->url }}">{{ $profile->url }}</a>
            @endif
            @if (\App\Services\HelperService::showAttribute($setting, $profile, \App\Services\HelperService::LOCATION))
                <p class="address">
                    {{{ \App\Services\HelperService::formatAddress($profile, $langAddress) }}}
                </p>
            @endif
        </div>
        <div class="col-md-4 col-lg-4 box-info-list">
          <div class="info-list-like">
            <a class="row" href="{{ URL::action('UsersController@getFollowingCategories', $user->username) }}">
              <p class="col-xs-8 col-md-8 col-lg-8">{{ trans('labels.following_categories_1') }}</p>
              <p class="col-xs-4 col-md-4 col-lg-4 number">{{ $user->followingCategories()->count() }}</p>
            </a>
            <a class="row" href="{{ URL::action('UsersController@getFollowing', $user->username) }}">
              <p class="col-xs-8 col-md-8 col-lg-8">{{ trans('labels.following_users') }}</p>
              <p class="col-xs-4 col-md-4 col-lg-4 number followingNumbers {{ $user->isCurrent() ? 'owner' : '' }}">{{ $user->following()->count() }}</p>
            </a>
            <a class="row" href="{{ URL::action('UsersController@getFollowers', $user->username) }}">
              <p class="col-xs-8 col-md-8 col-lg-8">{{ trans('labels.followers') }}</p>
              <p class="col-xs-4 col-md-4 col-lg-4 number followersNumber {{ $user->isCurrent() ? 'owner' : '' }}">{{ $user->followers()->count() }}</p>
            </a>
            <a class="row" href="{{ URL::action('UsersController@getShow', $user->username) }}">
              <p class="col-xs-8 col-md-8 col-lg-8">{{ trans('labels.posts') }}</p>
              <p class="col-xs-4 col-md-4 col-lg-4 number">{{ $user->publishedPosts()->count() }}</p>
            </a>
            <a class="row" href="{{ URL::action('UsersController@getStock', $user->username) }}">
              <p class="col-xs-8 col-md-8 col-lg-8">{{ trans('labels.stock') }}</p>
              <p class="col-xs-4 col-md-4 col-lg-4 number">{{ $user->stockPosts()->count() }}</p>
            </a>
          </div>
        </div>
    </div>
</div>
