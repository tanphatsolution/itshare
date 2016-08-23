@extends('layouts.default')
@section('css')
@stop
@section('main')
    <div class="col-md-12 setting">
        <div class="alert alert-success text-center" role="alert">
            @if(isset($newUser) && $newUser)
                {{ trans('messages.home.user_activated') }}
            @else
                {{ trans('messages.home.social_registered') }}
            @endif
        </div>
    </div>
@stop
@section('script')
    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

        ga('create', 'UA-60613670-1', 'auto');
        ga('create', 'UA-60613670-2', 'auto', {'name': '2ndTracker'});
        ga('send', 'event', 'button', 'click', 'register', 1);
        ga('2ndTracker.send', 'event', 'button', 'click', 'register', 1);
        var redirectUrl = "{{  isset($returnUrl) ? $returnUrl : action('UsersController@getLogin') }}";
    </script>
    {{ HTML::script('js/registration_finish.js') }}
    <script>
        fbq('track', 'CompleteRegistration');
    </script>
@stop
