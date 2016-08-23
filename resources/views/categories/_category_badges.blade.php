<div class="tags">
    <div class="tag-item">
        @if (count($categories))
            @foreach ($categories as $key => $category)
                <a class="{{ App\Services\HelperService::showCategoryLabel($category) }} tag-name" href="{{ url_to_category($category) }}" title="{{{ $category->name }}}">{{{ $category->name }}}</a>
            @endforeach
        @else
            <a class="other" href="#" title="{{{ trans('categories.other_title') }}}">{{{ trans('categories.other_title') }}}</a>
        @endif
    </div>
    <div class="tag-see-more" >
        <a tabindex="0" class="other show-more-tag bs-docs-popover"
           href="javascript:void(0);" data-trigger="focus"
           data-toggle="popover"
           data-content=''>{{ trans('categories.more_symbol') }}</a>
    </div>
</div>
