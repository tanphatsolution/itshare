@include('layouts.includes.head')

<div class="user-login col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2" style="margin-top:20px">
    <div class="panel panel-info" >
        <div class="panel-heading">
            <div class="panel-title">
                {{ trans('messages.user.user_access_account', ['name' => $currentUser->name, 'client' => $client->getName()]) }}
            </div>
        </div>
        {{ Form::open(['method' => 'POST', 'class' => 'form-horizontal', 'url' => route('oauth.authorize.post', $params)]) }}
            <br>
            <div class="form-group">
                <ul>
                    <li>{{ trans('messages.user.use_basic_profile') }}</li>
                    <li>{{ trans('messages.user.access_your_group') }}</li>
                </ul>
            </div>
            {{ Form::hidden('client_id', $params['client_id']) }}
            {{ Form::hidden('redirect_uri', $params['redirect_uri']) }}
            {{ Form::hidden('response_type', $params['response_type']) }}
            {{ Form::hidden('state', $params['state']) }}
            <div class="input-group mg-bottom-15" style="margin-left: 10px;">
                {{ Form::submit('Approve', ['name' => 'approve', 'value' => 1, 'class' => 'btn btn-primary']) }}
                {{ Form::submit('Deny', ['name' => 'deny', 'value' => 1, 'class' => 'btn btn-default', 'style' => 'margin-left: 10px']) }}
            </div>
        {{ Form::close() }}
    </div>
</div>
