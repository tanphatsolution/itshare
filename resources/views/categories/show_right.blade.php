<div class="col-md-3 col_right">
    <div class="list-group">
        <div class="list-group-item active">{{ trans('categories.stocked_user_ranking') }}</div>
        @foreach($category->getStokedUserRanking()->take(10)->get() as $stockedUserRanking)
        {{--*/ $user = User::find($stockedUserRanking->user_id) /*--}}
        <div class="list-group-item">
            <span class="badge">{{ $stockedUserRanking->stocks_count }}</span>
            {{ link_to_user($user) }}
        </div>
        @endforeach
    </div>
    <div class="list-group">
        <div class="list-group-item active">{{ trans('categories.top_stocker_post_last_week', ['category' => $category->name]) }}</div>
        @forelse (Post::getStockedRankingByCategoryId($category->id) as $post)
            <div class="list-group-item">
                <span class="badge">{{ $post->stocksCount }}</span>
                {{ link_to_post($post) }}
            </div>
        @empty
            <div class="alert alert-warning" role="alert">
                {{ trans('messages.category.message_warning')}}
            </div>
        @endforelse
    </div>
    <div class="list-group">
        <div class="list-group-item active">{{ trans('labels.share_page') }}</div>
        {{ Shareable::all() }}

    </div>
</div>
