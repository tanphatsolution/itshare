{{ isset($lang) ? App::setLocale($lang) : App::setLocale('en') }}
<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<h2>{{ trans('messages.user.reset_password_title') }}</h2>

		<div>
			{{ trans('messages.user.reset_password_message') }} {{ URL::to('password/reset', array($token)) }}.<br/>
            {{ trans('messages.user.reset_password_expire', ['time' => Config::get('auth.reminder.expire', 60) ]) }}
		</div>
	</body>
</html>
