{{ Form::open(['route' => ['categoryfollow.store'], 'method' => 'post', 'id' => 'category-follow']) }}
   {{ Form::hidden('category_id', $category->id) }}
    <button class="btn-follow-mini" type="submit" >{{ trans('messages.category.follow') }}</button>
{{ Form::close() }}