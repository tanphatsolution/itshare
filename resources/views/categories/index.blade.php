@extends('layouts.default')

@section('css')
    {{ HTML::style(version('css_min/categories_index.min.css')) }}
@stop

@section('main')

<div class="container category-list">
    <div class="row category-container">
        @include('categories._list_categories', ['categories' => $categories])
    </div>
    @if ($categories->count() >= \App\Services\CategoryService::CATEGORIES_PER_PAGE)
        <div class="load-more fake-link">
            <a id="see-more-category" data-message="{{ trans('messages.loading') }}">
                {{ trans('labels.load_more') }}
            </a>
        </div>
    @endif
</div>
@stop

@section('script')
    <script type="text/javascript">
        var username = null;
    </script>
    {{ HTML::script(version('js_min/categories_index.min.js'), ['defer' => 'defer']) }}
@stop

