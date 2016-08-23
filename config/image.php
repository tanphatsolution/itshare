<?php

return [
    'max_image_size' => 6000, // 6000KB
    'images_per_month' => 300, // 300 images
    'storage_per_month' => 200, // 200 MB
    'limited_storage' => 1000, // 1000 MB,
    'base64' => 'data:image/png;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=', // 1B,
    'category' => [
        'upload_dir' => 'img/categories',
        'upload_dir_65' => 'img/categories/65',
        'upload_dir_320' => 'img/categories/320',
        'image_default' => 'no-img.png',
    ],
    'theme_thumb' => [
        'upload_dir' => 'img/themes',
        'slide_thumbnail_default' => 'img/slide_thumbnail_default.png',
    ],
    'group_image' => [
        'cover_upload_dir' => 'img/groups/cover',
        'profile_upload_dir' => 'img/groups/profile',
        'post_thumbnail_default' => 'img/post_thumbnail_default.png',
        'group_cover_default' => 'img/img-groupdetail.jpg',
        'profile_no_image' => 'img/img-viblo.png'
    ],
    'video_introduction_link' => 'https://www.youtube.com/watch?v=PbFnVNvwcHg'
];
