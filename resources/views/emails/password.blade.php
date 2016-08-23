{{ trans('messages.reset_password.mail_content') }}:
<br/> <a href="{{ url('password/reset/'.$token) }}" target="_blank">{{ url('password/reset/'.$token) }}</a>