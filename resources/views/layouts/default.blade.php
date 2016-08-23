<!DOCTYPE html>
<!--[if IE 9]><html class="lt-ie10" lang="en" > <![endif]-->
<html lang="en">
<head>
    @include('layouts.includes.head')
    @include('layouts.includes.google_analystics')
</head>

<body screen="{{{ isset($screen) ? $screen : '' }}}">
    @include('layouts.includes.header')
    <div id="wrapper">
        @yield('slider')
        <div id="container" class="container">
            @if (isset($keyword))
                @include('search.search_header', ['keyword', $keyword])
            @endif

            @yield('main')
        </div>
        <div id="footer">
            @include('layouts.includes.footer')
        </div>
    </div>
    @include('layouts.includes.script')
    @if (!App\Facades\Authority::check() || !isset($currentUser))
        @include('modals.login_form')
    @endif
@include('layouts.includes.facebook_js_sdk')
</body>
</html>