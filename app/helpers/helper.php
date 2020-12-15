<?php

class helper
{
    public function ContactPlugin($pluginNamespace, $pluginFunction, $arguments)  // [Function]  Call Dynamic with dynmaic Class with function number of arguments.
    {
        if($pluginNamespace[0] == "*")
        {
            $pluginNamespace = $this->getPluginNamespace($pluginNamespace);
        }
        return call_user_func_array(array($pluginNamespace, $pluginFunction), $arguments);  // [return]  $arguments Represents an array with all arguments wich are
        //passed like this: functionname($arg1, $arg2, $arg3).
    }

    public function ContactPluginEW($pluginNamespaceAndFunction, $arguments)  // [Function]  Call Dynamic with dynmaic Class with function number of args.EasyWay
    {
        $pluginQuery = explode("@", $pluginNamespaceAndFunction);
        $pluginNamespace = $pluginQuery[0];
        $pluginFunction = $pluginQuery[1];
        return call_user_func_array(array($pluginNamespace, $pluginFunction), $arguments);  // [return]  $arguments Represents an array with all arguments wich are
        //passed like this: functionname($arg1, $arg2, $arg3).
    }

    public function ContactMultiFunctions($queryObjectWithAllData)  // [Function]  Call multiple functions
    {
        $result = [];
        foreach ($queryObjectWithAllData as $item) {
            $pluginNamespace = $item["class"];
            $pluginFunction = $item["func"];
            $pluginArguments = $item["args"];
            $pluginMeta = $item["meta"];
            $result[$pluginMeta] = call_user_func_array(array($pluginNamespace, $pluginFunction), $pluginArguments);
        }
        return $result;  // [Return]  Return all called function results in one object.
    }

    public function getPluginNamespace($pluginName)
    {
        //
    }
}

