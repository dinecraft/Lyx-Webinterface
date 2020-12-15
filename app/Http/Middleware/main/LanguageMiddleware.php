<?php

namespace App\Http\Middleware\main;

use Closure;
use Session;

class LanguageMiddleware
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
        $lang = Session()->get("main_lang"); //die für den user gesetze sprache ermitteln
        \App::setLocale($lang);
        return $next($request);
    }
}
