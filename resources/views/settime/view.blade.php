<h1>Server time: {{ $serverTime }}</h1>

{{ Form::open(['action' => 'SetTimeController@postForm', 'class' => 'form-horizontal col-md-10 col-md-offset-1 col-sm-12', 'role' => 'form']) }}
    <div class="form-group">
        {{ Form::label('weeklyDate', 'weeklyDate', ['class' => 'col-md-4 control-label']) }}
        <div class="col-md-8">
            {{ Form::text('weeklyDate', $weeklyDate) }}
        </div>
    </div>

    <div class="form-group">
        {{ Form::label('weeklyTimeStart', 'weeklyTimeStart', ['class' => 'col-md-4 control-label']) }}
        <div class="col-md-8">
            {{ Form::text('weeklyTimeStart', $weeklyTimeStart) }}
        </div>
    </div>

    <div class="form-group">
        {{ Form::label('weeklyTimeEnd', 'weeklyTimeEnd', ['class' => 'col-md-4 control-label']) }}
        <div class="col-md-8">
            {{ Form::text('weeklyTimeEnd', $weeklyTimeEnd) }}
        </div>
    </div>

    <div class="form-group">
        {{ Form::label('monthlySendDate', 'monthlySendDate', ['class' => 'col-md-4 control-label']) }}
        <div class="col-md-8">
            {{ Form::text('monthlySendDate', $monthlySendDate) }}
        </div>
    </div>

    <div class="form-group">
        {{ Form::label('monthlySendTime', 'monthlySendTime', ['class' => 'col-md-4 control-label']) }}
        <div class="col-md-8">
            {{ Form::text('monthlySendTime', $monthlySendTime) }}
        </div>
    </div>

    <div class="form-group">
        {{ Form::label('noPostSendDate', 'noPostSendDate', ['class' => 'col-md-4 control-label']) }}
        <div class="col-md-8">
            {{ Form::text('noPostSendDate', $noPostSendDate) }}
        </div>
    </div>

    <div class="form-group">
        {{ Form::label('noPostSendTime', 'noPostSendTime', ['class' => 'col-md-4 control-label']) }}
        <div class="col-md-8">
            {{ Form::text('noPostSendTime', $noPostSendTime) }}
        </div>
    </div>

    <div class="form-group">
        {{ Form::label('dateOfWeekIndex', 'dateOfWeekIndex', ['class' => 'col-md-4 control-label']) }}
        <div class="col-md-8">
            {{ Form::text('dateOfWeekIndex', $dateOfWeekIndex) }}
        </div>
    </div>

    <div class="form-group">
        {{ Form::label('noOfWeek', 'noOfWeek', ['class' => 'col-md-4 control-label']) }}
        <div class="col-md-8">
            {{ Form::text('noOfWeek', $noOfWeek) }}
        </div>
    </div>
    <br/>
    <div class="pull-left">
        {{ Form::submit('Set Time', ['class' => 'btn btn-success'])}}
    </div>
{{ Form::close() }}
<br/>
<p>----------------------------------</p>
<p>Get user information</p>
{{ Form::open(['action' => 'SetTimeController@getUser', 'class' => 'form-horizontal col-md-10 col-md-offset-1 col-sm-12', 'role' => 'form']) }}
<div class="form-group">
    {{ Form::label('email', 'email', ['class' => 'col-md-4 control-label']) }}
    <div class="col-md-8">
        {{ Form::text('email') }}
    </div>
</div>
<div class="pull-left">
    {{ Form::submit('Show detail', ['class' => 'btn btn-success'])}}
</div>
{{ Form::close() }}
<p>----------------------------------</p>
<p>Update user information</p>
{{ Form::open(['action' => 'SetTimeController@postUser', 'class' => 'form-horizontal col-md-10 col-md-offset-1 col-sm-12', 'role' => 'form']) }}
    <div class="form-group">
        {{ Form::label('username', 'username', ['class' => 'col-md-4 control-label']) }}
        <div class="col-md-8">
            {{ Form::text('username') }}
        </div>
    </div>
    <div class="form-group">
        {{ Form::label('email', 'email', ['class' => 'col-md-4 control-label']) }}
        <div class="col-md-8">
            {{ Form::text('email') }}
        </div>
    </div>
    <div class="form-group">
        {{ Form::label('work_email', 'work_email', ['class' => 'col-md-4 control-label']) }}
        <div class="col-md-8">
            {{ Form::text('work_email') }}
        </div>
    </div>
    <div class="pull-left">
        {{ Form::submit('Update email', ['class' => 'btn btn-success'])}}
    </div>
{{ Form::close() }}
<br/>
<p>----------------------------------</p>
{{ Form::open(['action' => 'SetTimeController@clearOffset','role' => 'form']) }}
    {{ Form::submit('Remove offset file', ['class' => 'btn btn-success'])}}
{{ Form::close() }}
<br/>
<p>----------------------------------</p>
{{ Form::open(['action' => 'SetTimeController@sendEmail','role' => 'form']) }}
    {{ Form::submit('Test send email', ['class' => 'btn btn-success'])}}
{{ Form::close() }}
<br/>
<p>----------------------------------</p>
{{ Form::open(['action' => 'SetTimeController@readLog','role' => 'form']) }}
    {{ Form::submit('Get log', ['class' => 'btn btn-success'])}}
{{ Form::close() }}
