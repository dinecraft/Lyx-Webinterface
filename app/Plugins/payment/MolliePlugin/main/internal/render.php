<?php
namespace App\Plugins\Native\SetupPlugin\Main\Internal;

use File;
use Session;
use Illuminate\Foundation\helpers; //to use csrf_token();
use eftec\bladeone\BladeOne;

class render
{
    //run the rendering process, call from here the renderer Method
    public function view($view, $data = [])
    {   //get view and resolve the . to \ (for folder-structure)
        $path = __DIR__ . "\\views\\" . str_replace(".", "\\", $view); //all view located in "views" - subfolder-struct
        $path .= ".view.html";
        $html = File::get($path); //get html file
        $result = $this->renderer($html, $data); //call render method to render
        return $result; //return result to requested plugin_controller
    }

    public function blade($view = "hey", $data = [])
    {
        $views = __DIR__ . '../frontend/views';
        $cache = __DIR__ . '/cache';
        $blade = new BladeOne($views,$cache,BladeOne::MODE_DEBUG); // MODE_DEBUG allows to pinpoint troubles.
        return $blade->run("hello",array("variable1"=>"value1")); // it calls /views/hello.blade.php
    }


    //render the html
    public function renderer($html, $data)
    {
        //replace all values | EXAMPLE : view("index", ["key" => "val"]); | @(key)
        foreach($data as $key => $item)
        {
            //replace
            $rplc = "@(".$key.");";
            $html = str_replace($rplc, $item, $html);
        }

        //replace / trnaslate all @[word]
        $splitted = explode("@[", $html);
        foreach($splitted as $item)
        {
            $task = explode("];", $item)[0];
            $string = $this->translator($task); //trnaslate the word
            $replace = "@[".$task."];";
            $html = str_replace($replace, $string, $html);
        }

        //include css | @css["folder.subfolder.filename"];
        $splitted = explode("@css[\"", $html);
        foreach($splitted as $item)
        {
            $task = explode("\"];", $item)[0];
            $string = $this->getFile("css", $task);
            $replace = "@css[\"".$task."\"];";
            $html = str_replace($replace, $string, $html);
        }

        $html = str_replace("@csrf;", csrf_field(), $html); //replace csrf;
        return $html; //return to view() function
    }


    //read file and return content
    public function getFile($type, $task)
    {
        $filepath = __DIR__ . "\\" . str_replace(".", "\\", $task); //resolve path
        $filepath .= ".".$type; //add file ending
        if(! file_exists($filepath)) {return ""; }
        $result = File::get($filepath);
        $result = "<style>".$result."</style>";
        return $result;
    }



    //gettet den übersetzen string
    public function translator($task)
    {
        $lang = $this->Lang("GET"); //get current lang code (example: de, en, fr)
        $filepath = __DIR__ . "\\LangFiles\\" . $lang . ".json"; //load trnalation file
        $file = File::get($filepath); // ...
        $file_object = json_decode($file); //json decode it

        //check if word / key exists
        if(isset($file_object->$task))
        {
            return $file_object->$task; //retrun to renderer() function
        }
        return $task; //return the key if no trnaslation in requested languge is found
    }












    //get and set languages
    public function Lang($lang = "en")
    {
        if($lang == "GET")
        {
            $exist = Session()->get("main_lang");
            if(isset($exist))
            {
                return Session()->get("main_lang");
            }
            else
            {
                Session()->put(["main_lang" => "en"]);
                return Session()->get("main_lang");
            }
        }
        else
        {
            Session()->put(["main_lang" => $lang]);
            return Session()->get("main_lang");
        }
    }
}