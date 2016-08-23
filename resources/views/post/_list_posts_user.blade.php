@foreach ($posts as $post)
	@if(is_null($post->categories))
		@include('groups._a_series', ['series' => $post, 'group' => $post->group, 'lang' => $lang])
	@else
		@include('post._a_post', ['post' => $post])
	@endif
@endforeach
