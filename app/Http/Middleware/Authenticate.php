<?php namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Auth;
use Request;
use Response;
use URL;
use Session;
use Redirect;

class Authenticate {

	/**
	 * The Guard implementation.
	 *
	 * @var Guard
	 */
	protected $auth;

	/**
	 * Create a new filter instance.
	 *
	 * @param  Guard  $auth
	 */
	public function __construct(Guard $auth)
	{
		$this->auth = $auth;
	}

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		if (is_null(Auth::id())) {
			if (Request::ajax()) {
				return Response::make('Unauthorized', 401);
			} else {
				if (!initReturnUrl()) {
					Session::put('returnUrl', URL::current());
				}
				return Redirect::route('getLogin');
			}
		}
		return $next($request);
	}

}
