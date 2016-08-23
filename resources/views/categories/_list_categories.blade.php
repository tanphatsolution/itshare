@foreach($categories as $category)
    <div class="col-md-4 col-lg-4 category">
        <a class="ctg-logo" href="{{ url_to_category($category->short_name) }}">
        <img src="{{ asset('img/blank.png') }}" style="background: url({{ $category->getImage() }})"></a>
        <div class="ctg-description">
            {{ link_to_category($category, ['class' => 'name']) }}
            <a class="number-post">{{ $category->posts_count }} {{ trans('labels.posts') }}</a>
            <a class="number-follow">
            <span id="category-follow-count-{{ $category->id }}">{{ $category->followersCount }}</span> {{ trans('labels.followers') }}</a>
            @include("categories.category_follow")
        </div>
    </div>
@endforeach
