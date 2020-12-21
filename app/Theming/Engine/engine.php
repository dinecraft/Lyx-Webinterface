<?php

namespace App\Theming\Engine;

use App\Theming\themeHandler;
use File;

class engine
{

    public $htmlCodeList = [
        "@" => "&#64;",
        "{" => "&#123;",
        "}" => "&#125;",
        "&" => "&#38;",
        "|" => "&#124;",
        "/" => "&#47;",
        "?" => "&#63;",
        "*" => "&#42;",
        ":" => "&#58;",
        ";" => "&#59;",
        "=" => "&#61;",
        "+" => "&#43;",
        "-" => "&#8722;",
        "<" => "&#60;",
        ">" => "&#62;",
        "." => "&#46;",
        "," => "&#44;",
    ];

    public $debug = "ignore";
    public $currentLevel = [];
    public $refCounter = 0;
    public $refArray = [];
    public $LevelStoreData = [];

    public function mainThreadRender($content, $dataArray = [])
    {
        $this->debug = $dataArray["__debug"] ?? "disabled";

        $start_exec_time = round(microtime(true) *1000);
        //All functions to result:
        $result_content = $content;
        $result_content = $this->handleEscapedStates($result_content);
        $result_content = $this->handleDynamicVariables($result_content, $dataArray);
        $result_content = $this->handleExternalSources($result_content);
        // $result_content = $this->handleFileIncludes($result_content);
        //END ALL. Functions to result.

        $end_exec_time = round(microtime(true) *1000) - $start_exec_time;
        //dd($result_content, ($end_exec_time));
        return ($result_content); // [RETURN] MAIN.
    }




    // ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::BEGINNNNN.


    //:[FunctionHandler] Import external sources.
    public function handleExternalSources($content)
    {
        //dd($this->getFileAndParse("*default.index"));
        $content = $this->getUseTagAndReplaceWithFile($content);
        return $content;
    }

    // [Function] find all @use() tags.
    public function getUseTagAndReplaceWithFile($content)
    {
        $match_array = [];
        preg_match_all("/(?<=(@use\())[^)]*(?=(\);))/s", $content, $match_array, PREG_PATTERN_ORDER);
        //dd($match_array);
        foreach ($match_array[0] as $match_raw)
        {
            $match = str_replace('"', "", $match_raw);
            $match = str_replace("'", "", $match);
            $file = $this->getFileAndParse($match);
            $file_content = $this->mainThreadRender($file);
            $content = str_replace("@use(".$match_raw.");", $file_content, $content);
        }
        return $content;
    }


    // :[FunctionHandler] Handle dynamic Variables replacement.
    public function handleDynamicVariables($content, $dataArray)
    {
        $match_result = [];
        preg_match_all("/(?<=[^\\\]{{\s)[^}]*(?=\s}})/s", $content, $match_result, PREG_PATTERN_ORDER);
        foreach ($match_result[0] as $match)
        {
            $content = str_replace("{{ ".$match." }}", $this->helperVarUnderstander($match, $dataArray), $content);
        }

        return $content;
    }

    // [HELPER] Understand the synstax of an variable or array an get the value back.
    public function helperVarUnderstander($var_raw, $dataArray)
    {
        $var = preg_replace("/\\$/s", "", $var_raw, 1);
        $var = str_replace("[", "->", $var);
        $var = str_replace("]", "", $var);
        $var = str_replace("'", "", $var);
        $var = str_replace('"', "", $var);
        $array_ofVar = explode("->", $var) ?? [$var];
        if(count($array_ofVar) <= 1)
        {
            return $dataArray[$array_ofVar[0]] ?? $this->handleErr("Can't find requested Varname for '$var_raw'", "Non existing Variable, undefined.");
        }
        else
        {
            return $this->understandArrayAndGetVal($array_ofVar, $dataArray);
        }
    }

    // [Funktion] Understand and resolve an array and return the requested value
    public function understandArrayAndGetVal($array, $dataArray)
    {
        $res = $dataArray;
        foreach ($array as $item)
        {
            $res = $res[$item] ?? $this->handleErr("Invalid Array Level in Array", "undefined");
        }
        return $res;
    }


    // :[FunctionHandler] Ignore all comments and escape all escaped states - then return $content.
    public function handleEscapedStates($content)
    {
        $content = $this->referenceIgnoreStatements($content);
        $content = $this->ignoreComments($content);
        $content = $this->escapeChars($content);
        return $content;
    }

    // [Function] Reference Ignor cases.
    public function referenceIgnoreStatements($content)
    {
        $match_result = [];
        preg_match_all("/@ignore:[^@]*@endignore;/s", $content, $match_result, PREG_PATTERN_ORDER);
        //dd($match_result);
        foreach ($match_result[0] as $match)
        {
            $content = str_replace($match, $this->referenceStringSet($match), $content);
        }
        return $content;
    }

    // [Function] Ignore all comments (between // and ;;).
    public function ignoreComments($content)
    {
        $content = str_replace("\/", "&#47;", $content); // [replace] escaped /
        $content = str_replace("\\\\", "&#92;", $content); // [replace] escaped \
        $content = str_replace("\n", " ", $content); // [remove] all newlines.
        $match_result_comments = [];
        preg_match_all("/\/\/[^;;]*;;/", $content, $match_result_comments, PREG_PATTERN_ORDER);
        foreach($match_result_comments[0] as $match)
        {
            $content = str_replace($match, "", $content);
        }
        return $content;
    }

    // [Function] escape all \<CHAR> in whole content.
    public function escapeChars($content)
    {
        foreach ($this->htmlCodeList as $key => $val)
        {
            $content = str_replace("\\".$key, $val, $content);
        }
        return $content;
    }



    //------------------------------------------------------------------------------------------------ Helpers

    // [ReadFile helper] Read and write files.
    public function getFileAndParse($filepath)
    {
        $filepath = str_replace("*", app_path()."/Theming/", $filepath);
        $filepath = str_replace(".", "/", $filepath);
        $fileAsString = File::get($filepath.".tem.html"); // [read] file;
        return $fileAsString;  // [Return]  the file as string.
    }


    // [Reference Helper] Used to make references.
    public function referenceStringSet($string)
    {
        $this->refCounter += 1;
        $this->refArray[$this->refCounter] = $string;
        return "&ref=".$this->refCounter."::ref";
    }

    public function referenceStringGet($string)
    {
        $res = explode("=", $string)[1];
        $res = explode("::", $string)[0];
        return $this->refCounter[$res];
    }

    // [Error System Fallback] could be called on error, its reporting the error and dies.
    public function handleErr($error = "[Unknown]", $initial = "[No further Information.]")
    {
        if($this->debug == "enabled")
        {
            print_r("<center><h2>An error occured: ".$error."<br><b>".$initial."</b></h2></center>");
            die;
        }
        else if($this->debug == "ignore")
        {
            return;
        }
        else
        {
            print_r("An error occured.");
            die;
        }
    }

}
