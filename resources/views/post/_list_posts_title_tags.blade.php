@foreach ($posts as $post)
    @include('post._a_post', ['post' => $post])
@endforeach
