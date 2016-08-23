@if ($currentUser)
<div class="category-follow-container">
    @if ($category->isFollowingBy($currentUser))
        @include('categories._unfollow')
    @else
        @include('categories._follow')
    @endif
</div>
@endif