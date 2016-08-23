<h5>
    <a class="follow-label" href="{{ URL::action('UsersController@getFollowingCategories', $user->username) }}">{{ trans('labels.following_categories') }} <span>{{ $user->followingCategories()->count() }}</span></a>
</h5>
{{--*/ $followingCategories = $user->followingCategories()->take(6)->get() /*--}}
<ul class="list-inline category-list">
@foreach($followingCategories as $category)
    <li>
        {{ link_to_category($category) }}
    </li>
@endforeach
</ul>