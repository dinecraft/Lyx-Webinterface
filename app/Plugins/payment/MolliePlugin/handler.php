<?php
namespace App\Plugins\Payment\MolliePlugin;
use App\Plugins\Payment\MolliePlugin\Main\Internal\router;

//The [Plugin]/handler.php is the first contacted file, wich is reciving and sending and coordinating all incoming data (POST/GET).

class handler
{
    public function route($route, $request)
    {
        //die route wird als string gegettet, von /r/ oder /rs/
        $route_array = explode("/", $route);
        //die url wird geparsed, index steht für den index der geparsten inline variablen
        $url_result = "";
        $url_parameter = [];
        $index = 0;
        //der url paramter muss ein "=" an erster stelle haben, um als paramter erkannt zu werden
        foreach($route_array as $item)
        {
            if($item[0] == "=")
            {
                $url_parameter[$index] = str_replace("::", " ", str_replace("=", "", $item));
                $url_result .= "/:"."PARAM";
                $index += 1;
            }
            else{
                $url_result .= "/".$item; //if not param
            }
        }

        //call routingHandler
        $r = new router();
        //return to controller (backendcontroller)
        return $r->web($url_result, $url_parameter, $request);
    }

    public function callDirect($call, $data)
    {
        try
        {
            //function to call directly from controller without routing
            $class = explode("@", $call)[0];
            $function = explode("@", $call)[1];
            $class = str_replace("*", __NAMESPACE__ . "\main\controllers\\", $class);
            $caller = new $class();
            return $caller->$function($data);
        }
        catch (\Exception $e)
        {
            return "err||There was an Error";
        }
    }
}
