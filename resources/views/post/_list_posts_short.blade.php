@foreach($posts as $post)
    <div class="col-lg-12">
        {{ user_img_tag($post->user) }}{{ link_to_user($post->user) }} •
        {{ link_to_post($post) }}
    </div>
@endforeach