<?php


namespace App\Http\Middleware\Backend;

use Closure;
class Authenticate
{

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!auth()->check() || (auth()->user()->is_admin !== 1 && auth()->user()->type <= 0)) {
            return redirect()->route('backend.auth.login');
        }

        return $next($request);
    }
}
