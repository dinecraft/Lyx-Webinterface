<?php

namespace App\Theming\Engine;

use App\Theming\themeHandler;

class PineapplePen
{
    public $currenLevel = [];
    public $numcount = 0;
    public $LevelStoreData = [];


     // [INFO]  This is a Rendering Engine by Hall-of-Code - which is esspecialy developed for the OpenHCP Panel.
    public function MainThreadRenderer($content, $dataArray = [])
    {
        $content = $this->replaceAllComments($content);
        $content = $this->escapeCompilerInterrupt($content);
        $content = $this->includeOtherTemplates($content);
        $content = $this->detectLoops($content, $dataArray);
        $content = $this->replaceVariablesWithValues($content, $dataArray);
        return $content;
    }

    public function detectLoops($content, $dataArray)
    {
        $content = $this->nestedReplaceForEasyerCompile($content);
        $content = $this->getNestedLevelsToArray($content);
        return $content;
    }

    public function escapeCompilerInterrupt($content)
    {
        $content = preg_replace("/(\])/s", "::ec_closed<", $content);
        return preg_replace("/(\[)/s", "::ec_open>", $content);
    }

    //replace {{ $some }} to value
    public function replaceVariablesWithValues($content, $dataArray)
    {
        preg_match_all("/\{\{\s[^\}]*\s\}\}/s", $content, $match_result, PREG_PATTERN_ORDER);
       //dd($match_result[0]);
        $test = [];
        foreach ($match_result[0] as $item)
        {
            //dd($item);
           // $content = str_replace($item, $this->understandArrayAndGetValue($item, $dataArray), $content);
            $content = preg_replace("/".$this->parseNewRegexConfirmString($item)."/s", $this->understandArrayAndGetValue($item, $dataArray), $content, 1);
        }
        return ($content);
    }

    public function understandArrayAndGetValue($item, $dataArray)
    {
        $item = preg_replace("/\{\{\s/s", "", $item, 1);
        $item = preg_replace("/\s\}\}/s", "", $item, 1);
        $item = str_replace("::ec_open>", "->", $item);
        $item = str_replace("::ec_closed<", "", $item);
        $matched_array = preg_split('/(->|\[[^\[]*\])/s', $item);
        if(count($matched_array) == 1) //wenn es nur ein argument gibt handelt es sich nicht um ein array.
        {
            $matched_array[0] = str_replace("$", "", $matched_array[0]);
            return $this->arrayParser($dataArray, $matched_array[0]);
        }

        $matched_array[0] = str_replace("$", "", $matched_array[0]);
        $result = $dataArray ?? [];
        foreach ($matched_array as $array_ident) {
            $result = $this->arrayParser($result, $array_ident);
        }
        return $result;
    }

    public function arrayParser($array, $data)
    {
        $res = $this->isString($data);
        if($res == "_FALSE")
        {
            return $array[$res];
        }
        return $array["$res"];
    }

    public function isString($string)
    {
        $string = str_replace("'", '"', $string);
        if($string[0] == '"' || !preg_match("/^\d+$/", $string))
        {
            return str_replace('"', '', $string);
        }
        return "_FALSE"; //is number.
    }

    //this compiles all elements to nesteds
    public function getNestedLevelsToArray($content)
    {
        for($c = 0; $c <= substr_count($content, "["); $c += 1)
        {
            //$c = level
            $content = str_replace("\n", "", $content);
            preg_match_all("/(\[(?:\[??[^\[]*?\]))/s", $content, $result_array, PREG_PATTERN_ORDER);
            $this->numcount = 0;
            foreach($result_array[0] as $result)
            {
               // dd($result);
                $this->numcount += 1;
                $g = "$c|N$this->numcount";
                $f = "&ref==(L";
                $k = ");";
                $all = $f . $g . $k;
                $usage_reference = $all;
                $this->LevelStoreData[$usage_reference] = "$result";
                $result = $this->parseNewRegexConfirmString($result);
                $content = preg_replace("/". $result ."/", $usage_reference, $content, 1);
            }
        }
        return ($content); //print_r($content);
    }

    public function parseNewRegexConfirmString($result)
    {
        $result = str_replace("\\", "\\\\", $result);
        $result = str_replace("[", "\[", $result);
        $result = str_replace("]", "\]", $result);
        $result = str_replace("|", "\|", $result);
        $result = str_replace("^", "\^", $result);
        $result = str_replace("$", "\\$", $result);
        $result = str_replace("(", "\(", $result);
        $result = str_replace(")", "\)", $result);
        $result = str_replace(".", "\.", $result);
        $result = str_replace("*", "\*", $result);
        $result = str_replace("+", "\+", $result);
        $result = str_replace("-", "\-", $result);
        return $result;
    }





    public function nestedReplaceForEasyerCompile($content)
    {
        $types_array = [
            "@each",
            "@endeach;",
            "@loop",
            "@endloop;",
            //"@style",
            //"@script",
            //"@import",
            //"@once",
            "@section",
            "@endsection;",
            "@select",
            "@endselect",
            //"@item",
            //"@element",
            //"@transstring",
            "@translate",
            "@endtranslate",
            "@if",
            "@else",
            "@endelse",
            "@endif;",
            "@form",
            "@endform;",
            //"@post",
            //"@get",
            "@live",
            "@liveend",
            //"@product",
            "@products",
            "@endproducts",
            //"@token;",
            //"@time",
            //"@auth",
            //"@var",
        ];

        $content = str_replace("@elseif", "@else", $content);

        foreach($types_array as $item)
        {
            $st = "[compile,type==";
            $item_w = str_replace("@", "", $item);
            $item_dataspace = $st . $item_w . "||"; //easyer readable for compiler because mor unique
            if(substr($item, 0, 4) == "@end")
            {
                $st = "==type,compile]";
                $item_w = str_replace("@", "", $item);
                $item_dataspace = $item_w . "<<" . $st; //easyer readable for compiler because mor unique
            };

            $content = str_replace($item, $item_dataspace, $content); //@something replaced in content with unique %%compiler...something
        }
        return $content;
    }


    public function parseArrayParameters($durchlauf, $query)
    {
        $array = explode("->", $query);
        $t = $durchlauf;
        foreach($array as $item)
        {
            $t = $t[$item];
        }
        return $t;
    }


    public function includeOtherTemplates($content)
    {
        $includes_array = explode("@use('", $content) ?? array("none");
        $renderInstance = new themeHandler();
        foreach ($includes_array as $item)
        {
            $includes_inh = explode("');", $item)[0] ?? "_testval";
            $toReplace = "@use('" . $includes_inh . "');";
            if($includes_inh == "_testval" || $includes_inh = $content)
            {
                return $content;
            }
            else
            {
                $result = $renderInstance->showPageFromTemplate($includes_inh, [], "pp");
                $content = str_replace($toReplace, $result, $content);
            }
        }

        return $content;
    }

    public function replaceAllComments($content)
    {
        // [first]  Replace all invalid PHP Statements for pasing Purpose (comments ect).
        $comment_array = explode(".//", $content) ?? array("none");
        foreach ($comment_array as $item) {
            $comment_inh = explode("*;", $item)[0] ?? ".//";
            $comment = ".//" . $comment_inh . "*;";
            $content = str_replace($comment, "", $content);
        }
        return $content;
    }
}
