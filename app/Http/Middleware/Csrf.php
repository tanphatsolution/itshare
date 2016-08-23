<?php namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Session\TokenMismatchException;
use Session;

class Csrf
{

    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * Create a new filter instance.
     *
     * @param  Guard $auth
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     * @throws TokenMismatchException
     */
    public function handle($request, Closure $next)
    {
        if (!$request->is('oauth/access_token')) {
            $token = $request->ajax() ? $request->header('X-CSRF-Token') : $request->get('_token');
            if (Session::token() !== $token) {
                throw new TokenMismatchException;
            }
        }
        return $next($request);
    }

}
