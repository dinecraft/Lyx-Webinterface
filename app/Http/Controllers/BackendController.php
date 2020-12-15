<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Session;
Use App;
use Config;
use Artisan;
use File;

class BackendController extends Controller
{
    //function zum langugeTest
   // public function language($lang)
    //{
      //  \App::setLocale($lang);
        //return view("setup.step1");
    //}

    //plugin routing 
    public function pluginRouting(Request $request, $url)
    {
        $splitted = explode("/", $url, 2);
        $plugin = "App\Plugins\Native\\".$splitted[0]."\handler";
        $result = new $plugin();
        if(isset($splitted[1])) {$route = $splitted[1]; } else {$route = "/"; }
        $result = $result->route($route, $request);

        return response($result);
    }
}
