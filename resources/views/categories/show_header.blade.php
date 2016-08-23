<div class="category-info">
    <div class="picture">
        <div class="outline">
            <div class="first-line">
                <h1 class="url-name">
                    <span itemprop="name">{{{ $category->name }}}</span>
                </h1>
            </div>
            <div class="second-line">
                <span class="count-post">{{ $category->posts()->count() }}</span>
                <span class="unit-post">{{ trans('messages.category.count_posts') }}</span>
            </div>
            <div class="second-line">
                <span class="count-post" id="category-follow-count-{{ $category->id }}">{{ $category->followers_count }}</span>
                <span class="unit-post">{{ trans('messages.category.count_followers') }}</span>
            </div>
            @include("categories.category_follow")
        </div>
    </div>
</div>