@if (isset($relationship->id) && $relationship->id != null)
    {{ Form::open(['route' => ['relationships.destroy', $relationship->id], 'data-action' => 'unfollow', 'method' => 'delete', 'id' => 'relationship-follow'])}}
    <button class="btn-follow-mini btn-following user_profile_btn_follow" type="submit"
            onmouseover="$(this).text('{{ trans('relationships.unfollow') }}')"
            onmouseout="$(this).text('{{ trans('relationships.following_lbl') }}')"
    >{{ trans('relationships.following_lbl') }} </button>
    {{ Form::close() }}
@endif

