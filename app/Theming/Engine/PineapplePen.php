<?php

namespace App\Theming\Engine;

use App\Theming\themeHandler;

class PineapplePen
{
     // [INFO]  This is a Rendering Engine by Hall-of-Code - which is esspecialy developed for the OpenHCP Panel.
    public function MainThreadRenderer($content, $dataArray)
    {
        //$content = $this->replaceAllComments($content);
        //$content = $this->includeOtherTemplates($content);
        $content = $this->detectLoops($content, $dataArray);
        return $content;
    }

    public function detectLoops($content, $dataArray)
    {
        $foreach_array = explode("@each('", $content) ?? "_testval";
        if($foreach_array == "_testval")
        {
            return $content;
        }

        foreach($foreach_array as $item) //das hier ist für jede foreahc schleife im Template
        {
            $foreach_space = explode("@endeach;", $item)[0];
            $foreach_header = explode("):", $foreach_space, 2)[0];
            $foreach_body = explode("):", $foreach_space, 2)[1];

            $foreach_header_cont = explode("(", $foreach_header, 2)[1];
            $foreach_index = explode(" as ", $foreach_header_cont,2)[0]; //der name des arrays welches der engine als paramter übergeb nwerden muss.
            $loopIndex = $dataArray[str_replace("$", "", $foreach_index)]; // ..
            $foreach_item = explode(" as ", $foreach_header, 2)[1]; //das synonym für den jeweiligen foreach durclauf des Indexes, hier wird nur der name definiert.
            $loopItem = $foreach_item;

            // [Processor] For each Processor:
            foreach($loopIndex as $durchlauf) // durchlauf ist das synonym für jeden durchlauf des indexes.
            {
                $item_array = explode("{{ ", $foreach_body);
                foreach($item_array as $statement) // hier wird jetzt jedes html element welches dem wert von $loopItem entspricht, durcluafen
                {
                    $statementt = explode(" }}", $statement)[0];
                   // if(strpos($statement, $loopItem) !== false)
                    //{
                    return $durchlauf;
                        $replace = $this->parseArrayParameters($durchlauf, explode("->", $statement)[1]);
                    //}

                    $content =  str_replace("{{ " . $replace . " }}", $content);
                }
                return $content;
            }
            // [Processor: end].
        }
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
            if($includes_inh != "_testval")
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
