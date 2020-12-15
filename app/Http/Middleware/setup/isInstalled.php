<?php

namespace App\Http\Middleware\setup;

use Closure;
use DB;
use Artisan;

class isInstalled
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        //if connection to database False
        try {
            DB::connection("mysql")->getPdo();
            return response("allready Installed");
        } catch (\Exception $e) {
            return $next($request);
        }
    }
}
