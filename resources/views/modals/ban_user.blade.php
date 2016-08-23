<div class="modal fade" id="banModal{{$user->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Ban {{{ $user->name }}}</h4>
            </div>
            <div id="message_error{{$user->id}}"></div>
            <div class="modal-body">
                {{ Form::open(['action' => ['UsersController@postBanUser', $user->id], 'method'=>'post', 'class' => 'form-horizontal ban_form', 'role' => 'form']) }}
                <form id="loginForm" method="post" class="form-horizontal">
                    {{ Form::hidden('user_id', $user->id) }}
                    <div class="form-group">
                        <label class="col-xs-3 control-label">{{ trans('labels.modal.lift_date') }}</label>
                        <div class="col-xs-5">
                            {{Form::text('lift_date', null, array('class' => 'form-control datepicker', 'data-provide' => 'datepicker', 'readonly' => true));}}
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-xs-3 control-label">{{ trans('labels.modal.reason_ban') }}</label>
                        <div class="col-xs-5">
                            {{Form::textarea('relason',null, array('class' => 'form-control'));}}
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-xs-5 col-xs-offset-3">
                            <button type="submit" class="btn btn-default">{{ trans('labels.modal.ban') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>