<?php namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Redirect;

class Authority
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
     * @param   $actions
     * @param   $resource
     * @param   $role
     * @return mixed
     */
    public function handle($request, Closure $next, $actions = [], $resource = null, $role = null)
    {
        if (!is_null($role) && !\App\Facades\Authority::hasRole($role)) {
            return Redirect::route('getTopPage');
        }
        if (!is_null($actions)) {
            $actions = explode('-', $actions);
        }
        if (\App\Facades\Authority::cannot($actions, $resource)) {
            return Redirect::route('getTopPage');
        }
        return $next($request);
    }

}
