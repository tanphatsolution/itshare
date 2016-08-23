{{ Form::open(['route' => ['relationships.store'], 'method' => 'post', 'data-action' => 'follow', 'id' => 'relationship-follow']) }}
   {{ Form::hidden('followed_id', $user['id']) }}
    <button class="btn-follow-mini  user_profile_btn_follow" type="submit" >{{ trans('relationships.follow') }}</button>
{{ Form::close() }}
