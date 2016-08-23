@foreach ($unapprovedPosts as $singlePost)
    <li class="unapproved-post">
        {{ HTML::link(url_to_post($singlePost->post), $singlePost->post->title) }}
        <p>
            <a href="javascript:void(0)" class="dic deny-group-post"
                data-group-encrypted-id="{{ $group->encrypted_id }}" data-post-encrypted-id="{{ $singlePost->post->encrypted_id }}"
                onclick="denyGroupPost(this)">
                {{ trans('labels.groups.deny') }}
            </a>

            <a href="javascript:void(0)" class="sub approve-group-post"
                data-group-encrypted-id="{{ $group->encrypted_id }}" data-post-encrypted-id="{{ $singlePost->post->encrypted_id }}"
                onclick="approveGroupPost(this)">
                {{ trans('labels.groups.approve') }}
            </a>
        </p>
    </li>
@endforeach

