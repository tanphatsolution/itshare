<ul class="member-admin">
@foreach ($userMembers as $member)
    @include('groups._a_member', ['user' => App\Data\System\User::where('id', $member->user_id)->first(), 'member' => $member])
@endforeach
</ul>