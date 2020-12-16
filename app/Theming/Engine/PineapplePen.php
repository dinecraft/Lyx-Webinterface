<?php

namespace App\Theming\Engine;

use App\Theming\themeHandler;
use function GuzzleHttp\Psr7\str;

class PineapplePen
{
    public $currenLevel = [];
    public $numcount = 0;


     // [INFO]  This is a Rendering Engine by Hall-of-Code - which is esspecialy developed for the OpenHCP Panel.
    public function MainThreadRenderer($content, $dataArray)
    {
        $content = $this->replaceAllComments($content);
        $content = $this->includeOtherTemplates($content);
        $content = $this->detectLoops($content, $dataArray);

        return $content;
    }

    public function detectLoops($content, $dataArray)
    {
        $content = $this->nestedReplaceForEasyerCompile($content);
        $content = $this->getNestedLevelsToArray($content);
        return $content;
    }




    //this compiles all elements to nesteds
    public function getNestedLevelsToArray($content)
    {
        for($c = 0; $c < ; $c += 1) {
            preg_match_all("/(\[(?:\[??[^\[]*?\]))/s", $content, $result_array, PREG_PATTERN_ORDER);
           // dd($result_array);
            $this->numcount = 0;
            foreach($result_array[0] as $result)
            {
                $this->numcount += 1;
                $g = "$c|N$this->numcount";
                $usage_reference = "&reference==(L" . $g . ");";
                $content = preg_replace("/$result/s", $usage_reference, $content, 1);
            }
        }
        return  ($content); //print_r($content);
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
            "@transalte",
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
