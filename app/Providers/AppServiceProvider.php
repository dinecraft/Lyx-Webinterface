<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Blade;
use Session;
use File;
use Lang;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        /**Blade::directive('langmod', function ($expression) {
            $lang = Session::get("main_lang");
            $path = resource_path() . '\lang'. '/' . $lang .'.json';
            if(!File::exists($path)) {return "<?php echo 'hey nop'; ?>"; }
            $json = File::get($path);
            $reval = json_decode($json, true);
            $result = $reval;
            return "<?php echo 'Hello {$result}'; ?>";
        });
        */
    }
}
