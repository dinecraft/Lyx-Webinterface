<?php

namespace App\helpers;

// [use]
use App\Models\PluginsList;

class helper
{
    public static function pluginNamespace($pluginName)
    {
        $result = PluginsList::where("pluginName", $pluginName)->first();
        return $result["pluginNamespace"];
    }

    public function ContactPlugin($pluginNamespace, $pluginFunction, $arguments)  // [Function]  Call Dynamic with dynmaic Class with function number of arguments.
    {
        if($pluginNamespace[0] == "*")
        {
            //$pluginNamespace = $this->getPluginNamespace($pluginNamespace);
        }
        $pluginNamespace = new $pluginNamespace();
        return call_user_func_array(array($pluginNamespace, $pluginFunction), $arguments);  // [return]  $arguments Represents an array with all arguments wich are
        //passed like this: functionname($arg1, $arg2, $arg3).
    }

    public function ContactPluginEW($pluginNamespaceAndFunction, $arguments)  // [Function]  Call Dynamic with dynmaic Class with function number of args.EasyWay
    {
        $pluginQuery = explode("@", $pluginNamespaceAndFunction);
        $pluginNamespace = new $pluginQuery[0];
        $pluginFunction = $pluginQuery[1];
        return call_user_func_array(array($pluginNamespace, $pluginFunction), $arguments);  // [return]  $arguments Represents an array with all arguments wich are
        //passed like this: functionname($arg1, $arg2, $arg3).
    }

    public function ContactMultiFunctions($queryObjectWithAllData)  // [Function]  Call multiple functions
    {
        $result = [];
        $counter = 0;
        foreach ($queryObjectWithAllData as $item) {
            $pluginNamespace = new $item["class"]();
            $pluginFunction = $item["func"];
            $pluginArguments = $item["args"] ?? null;
            $pluginMeta = $item["meta"] ?? $counter;
            if($pluginMeta == $counter)
            {
                $counter += 1;
            }

            if(is_array($pluginArguments))
            {
                $result["$pluginMeta"] = call_user_func_array(array($pluginNamespace, $pluginFunction), $pluginArguments);
            }
            else if($pluginArguments)
            {
                $class = new $pluginNamespace();
                $result["$pluginMeta"] = $class->$pluginFunction($pluginArguments);
            }
            else
            {
                $class = new $pluginNamespace();
                $result["$pluginMeta"] = $class->$pluginFunction();
            }
        }
        return $result;  // [Return]  Return all called function results in one object.
    }

    public function getPluginNamespace($pluginName)
    {
        //
    }
}

