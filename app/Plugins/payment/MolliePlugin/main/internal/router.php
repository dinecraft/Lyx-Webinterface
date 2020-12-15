<?php
namespace App\Plugins\Native\SetupPlugin\Main\Internal;

use DB;

class router
{
    //routing returns to handler.php
    public function web($url_path, $url_parameter, $data)
    {
        $error_handler = ""; 
        //link the Plugin/web.php as Routing File 
        $class = new $namespace();             
        $routes = $class->webRoutes();         //get all routes as Array 

        $caller = ""; //bug definer caller for handling class fatal issuies
        $function = ""; //same

        //check if route exists
        foreach($routes as $key => $item) 
        {
            if($key == $url_path)
            {
                $splitted = explode("@", $item);
                $path = str_replace("*", "main\controllers\\", $splitted[0]);
                $caller = __NAMESPACE__ . "" . $path;
                $function = $splitted[1];
                break;
            }
        }

        //error handling
        if($caller == "" || $function == "")
        {
            $error_handler .= "[ERROR: Cant find Class] Possible Solutions: [Route Not Found], [Class not definded], [Invalid namespace in Plugin/web.php]";
            return $error_handler;
        }

        //call the route definded controller@function
        $class = new $caller();
        $result = $class->$function($data, $url_parameter);
        return $result; //return to handler.php
    }
}