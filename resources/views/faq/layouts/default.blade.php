<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Viblo</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @include('faq.includes.head')
        @yield('css')
    </head>
    <body class="faq">
        @include('faq.includes.header')
        <div id="wrapper">
            <div id="container" class="container">
                @yield('main')
            </div>
            <div id="footer">
                @include('faq.includes.footer')
            </div>
        </div>
        @include('faq.includes.script')
        @yield('js')
    </body>
</html>
