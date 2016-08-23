@if (isset($currentUser) && $currentUser->id != $user['id'])
    <div class="relationship-container">
        @if ($currentUser->isFollowing($user))
            @include('relationships.unfollow', ['relationship' => App\Data\Blog\UserRelationships::findId($currentUser->id, $user['id'])->first()])
        @else
            @include('relationships.follow')
        @endif
    </div>
@endif
