<ul class="member-popup">
@foreach ($userMembers as $member)
    @include('groups._popup_a_member_detail', ['member' => $member])
@endforeach
</ul>