{{ Form::open(['route' => ['categoryfollow.destroy', $category->id], 'method' => 'delete', 'id' => 'category-unfollow'])}}
    <button class="btn-follow-mini btn-following" type="submit"
            onmouseover="$(this).text('{{ trans('messages.category.unfollow') }}')"
            onmouseout="$(this).text('{{ trans('messages.category.following') }}')"
            >{{ trans('messages.category.following') }}</button>
{{ Form::close() }}