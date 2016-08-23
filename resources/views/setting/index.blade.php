@extends('layouts.default')

@section('css')

{{ HTML::style(version('css_min/setting_index.min.css')) }}

@stop

@section('main')

<div class="col-md-12 setting">
	@include('layouts.includes.sidebar_setting')

	<div class="col-md-9 col-sm-8">
		<div class="alert alert-success text-center" role="alert">
		    {{ trans('messages.setting.manager') }}
		</div>
	</div>

</div>

@stop


@section('script')
@stop