<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Session;
Use App;
use Config;
use Artisan;
use File;

use App\helpers\helper;
use App\Models\PluginsList;

class BackendController extends Controller
{
    //function zum langugeTest
   // public function language($lang)
    //{
      //  \App::setLocale($lang);
        //return view("setup.step1");
    //}

    //plugin routing
    public function pluginRouting(Request $request, $plugin, $url) // [function] this function could call every plugin by its registered name
    {
        $plugin = strtolower($plugin);
        $pluginExists = PluginsList::where("pluginRoute", $plugin)->where("pluginInstalled", "1")->count();
        if($pluginExists <= 0)
        {
            return view("errors.notFound");
        }

        $pluginArray = PluginsList::where("pluginRoute", $plugin)->where("pluginInstalled", "1")->first();

        $plugin = $pluginArray["pluginNamespace"] . "\handler";
        $result = new $plugin();
        $route = $url;
        $result = $result->route($route, $request);

        return response($result); //returns the result of the Plugin
    }

    public function testing() // [only for testing]
    {
        $helper = new helper();
        $queryObjectWithAllData = array(
            ["func" => "testing", "class" => "App\Plugins\Native\PermissonsPlugin\handler"],
            ["func" => "testing", "class" => "App\Plugins\Native\PermissonsPlugin\handler"],
            ["func" => "testing", "class" => "App\Plugins\Native\PermissonsPlugin\handler"],
        );
        return response($helper->contactPluginEW("App\Plugins\Native\PermissonsPlugin\handler@testing", array("")));
    }
}
