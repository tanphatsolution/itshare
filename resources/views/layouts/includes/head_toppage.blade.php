<title>{{ isset($title) ? $title : Config::get('app.app_name') }}</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
<meta name="_token" content="{{ csrf_token() }}">
<meta name="description" content="{{ isset($description) ? $description : trans('app.app_description') }}">
<meta name="keyword" content="{{ isset($keywords) ? $keywords : trans('app.app_keyword') }}">
<!-- meta tag for facebook -->
<meta property="fb:app_id" content="{{ Config::get('facebook.appId') }}">
<meta property="og:title" content="{{ isset($title) ? $title : Config::get('app.app_name') }}">
<meta property="og:description" content="{{ isset($description) ? $description : trans('app.app_description') }}">
<meta property="og:url" content="{{ isset($url) ? $url : Config::get('app.url') }}">
<meta property="og:image" content="{{ asset(isset($siteImage) ? $siteImage : 'img/og-facebook-v2.png') }}">
<meta property="og:image:width" content="{{ isset($imageWidth) ? $imageWidth : 1200 }}">
<meta property="og:image:height" content="{{ isset($imageHeight) ? $imageHeight : 630 }}">
<meta property="og:type" content="website">
<meta property="og:site_name" content="{{ Config::get('app.app_name') }}">
<!-- meta tag for twitter -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:url" content="{{ isset($url) ? $url : Config::get('app.url') }}">
<meta name="twitter:title" content="{{ isset($title) ? $title : Config::get('app.app_name') }}">
<meta name="twitter:description" content="{{ isset($description) ? $description : trans('app.app_description') }}">
<meta name="twitter:image" content="{{ asset(isset($siteImage) ? $siteImage : 'img/og-facebook-v2.png') }}">
<meta name="google-site-verification" content="{{ Config::get('app.app_google_site_verification') }}">

<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon"/>

{{ HTML::style(version('css_min/home_sign_up.min.css'), ['async' => 'async']) }}