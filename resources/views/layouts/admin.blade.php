<!DOCTYPE html>
<!--[if IE 9]><html class="lt-ie10" lang="en" > <![endif]-->
<html lang="en">
<head>
    @include('layouts.includes.head')
</head>

<body>
@include('layouts.includes.facebook_js_sdk')
@include('layouts.includes.google_analystics')
    @include('layouts.includes.admin_header')

    <div id="wrapper">
        <div class="row">
            <input id="reloadValue" type="hidden" name="reloadValue" value="" />
            @yield('main')
        </div>
    </div>
    @include('layouts.includes.script')
    @if (!isset($currentUser))
        @include('modals.login_form')
    @endif
</body>
</html>