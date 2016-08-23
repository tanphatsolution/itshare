@extends('layouts.admin')

@section('css')

{{ HTML::style(version('css_min/contests_create.min.css')) }}
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
@include('elements.css.codemirror')

@stop

@section('main')
<div class="container-fluid">
    <div class="admin">
        @include('layouts.includes.sidebar_admin_setting_2')

        <div class="role col-md-10 col-sm-8 right-admin">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <div class="panel-title">{{ trans('labels.contest.title_create') }}</div>
                </div>
                <div class="panel-body" >
                    @if ($errors->has())
                        <div class="alert alert-danger">
                            @foreach($errors->all() as $message)
                                <p>{{ $message }}</p>
                            @endforeach
                        </div>
                    @endif
                    @if (Session::has('message'))
                        <div class="alert alert-success">
                            <p>{{ Session::get('message') }}</p>
                        </div>
                    @endif
                    {{ Form::open([
                            'action' => 'ContestController@store',
                            'class' => 'form-horizontal',
                            'role' => 'form'
                        ])
                    }}
                    <div class="form-group">
                        <label for="name" class="col-md-3 control-label">{{ trans('labels.contest.contest_title') }}</label>
                        <div class="col-md-6 input-group">
                            {{ Form::text('name', null, [
                                    'class' => 'form-control',
                                    'required' => 'required',
                                    'placeholder' => trans('messages.contest.enter_name')
                                ])
                            }}
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="theme" class="col-md-3 control-label">{{ trans('labels.contest.contest_theme') }}</label>
                        <div class="col-md-6 input-group">
                            {{ Form::select('monthly_theme_subject_id', $monthlyThemeSubject,
                                isset($themes['monthlyThemeSubjectId']) ? $themes['monthlyThemeSubjectId'] : null, [
                                    'onChange' => 'getMonthlyThemes()',
                                    'id' => 'monthly-theme-subject-id',
                                    'class' => 'form-control'
                                ])
                            }}
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="category" class="col-md-3 control-label">{{ trans('labels.contest.contest_tag') }}</label>
                        <div class="col-md-6 input-group">
                            {{ Form::text('category', null, [
                                    'class' => 'form-control',
                                    'id' => 'category-input'
                                ])
                            }}
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="email" class="col-md-3 control-label">{{ trans('labels.contest.contest_email') }}</label>
                        <div class="col-md-6 input-group">
                            {{ Form::text('email', null, [
                                    'class' => 'form-control',
                                    'id' => 'email-input'
                                ])
                            }}
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="start" class="col-md-3 control-label">{{ trans('labels.contest.contest_start') }}</label>
                        <div class="col-md-6 input-group">
                            {{ Form::input('text', 'start', '', [
                                    'id' => 'start',
                                    'class' => 'form-control',
                                    'required' => 'required'
                                ])
                            }}
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="end" class="col-md-3 control-label">{{ trans('labels.contest.contest_end') }}</label>
                        <div class="col-md-6 input-group">
                            {{ Form::input('text', 'end', '', [
                                    'id' => 'end',
                                    'disabled' => 'true',
                                    'class' => 'form-control',
                                    'required' => 'required'
                                ])
                            }}
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="end" class="col-md-3 control-label">{{ trans('labels.contest.contest_end_score') }}</label>
                        <div class="col-md-6 input-group">
                            {{ Form::input('text', 'score_end', '', [
                                    'id' => 'score_end',
                                    'class' => 'form-control',
                                    'required' => 'required'
                                ])
                            }}
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-offset-5 col-md-2">
                            {{ Form::submit(trans('buttons.post.create'), [
                                    'class' => 'btn btn-success form-control'
                                ])
                            }}
                        </div>
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
</div>

@stop

@section('script')
    <script type="text/javascript">
        var categories = {{ json_encode($categories) }};
        var emails = {{ json_encode($emails) }};
        var no_more = {{ json_encode(trans('messages.contest.email_warning')) }};
        var form = {{ json_encode(Form::select("email[]", array_diff($emails, [trans("messages.contest.none")]), "", [ "id" => "email", "class" => "emailDropdown" ]))}};
    </script>

    @include('elements.js.codemirror')
    {{ HTML::script(version('js_min/contest.min.js')) }}
@stop
