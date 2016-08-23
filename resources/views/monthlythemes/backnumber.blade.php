@extends('layouts.admin')

@section('css')

{{ HTML::style(version('css_min/monthlythemes_backnumber.min.css')) }}

@stop

@section('main')

<div class="container-fluid">
    <div class="admin">
    @include('layouts.includes.sidebar_admin_setting_2')
        <div class="right-admin col-lg-10">
            @if (Session::has('success'))
                <div class="alert alert-success" role="alert">
                    <p>{{ Session::get('success') }}</p>
                </div>
            @endif
            @if ($errors->has())
                <div class='alert alert-danger'>
                    @foreach($errors->all() as $message)
                        <p>{{ $message }}</p>
                    @endforeach
                </div>
            @endif
            @if ($currentMonthThemeSubject)
                <a href="{{ url_to_themes($currentMonthThemeSubject->short_name) }}" class="theme-of-month" title="{{ trans('labels.monthly_theme.back_number') }}">
                    <input class="creat" type="submit" value="{{ trans('labels.monthly_theme.back_number') }}">
                </a>
            @endif
            <div class="control-group choice">
                <label class="control-label col-lg-2">{{ trans('labels.monthly_theme.choose_month') }}</label>
                {{ Form::select('publish_month', $timeOptions['months'], isset($month) ? $month : null, ['onChange' => 'checkBackNumber()', 'id' => 'publish-month']) }}
                {{ Form::select('publish_year', $timeOptions['years'], isset($year) ? $year : null, ['onChange' => 'checkBackNumber()', 'id' => 'publish-year']) }}
            </div>
            <div class="edit-theme"></div>
            <div class="alert-not-create">
                <div class="alert alert-warning">{{ trans('messages.theme.not_create_warning') }}</div>
            </div>
        </div>

        <div class="clearfix"></div>
    </div>
</div>

@stop

@section('script')
    <script type="text/javascript">
        var noTheme = "{{ trans('messages.theme.no_theme') }}";
    </script>
    {{ HTML::script(version('js_min/monthlythemes_backnumber.min.js')) }}
@stop
