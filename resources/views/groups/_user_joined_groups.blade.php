@foreach ($userJoinedGroups as $userJoinGroup)
    @include('groups._a_user_joined_group', ['userJoinGroup' => $userJoinGroup, 'lang' => $lang])
@endforeach
