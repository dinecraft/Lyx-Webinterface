<?php

namespace App\Theming;

use App\Theming\Engine\engine; // [Use] Tempalting Engine
use File;

class themeHandler
{
    //testing
    public function testRender()
    {
        $start_exec_time = round(microtime(true) *1000);
        $realPath = $this->parseTemplatePath("default.index");
        $content = $this->readTemplateFileByPath($realPath);
        $dataArray = [
            "__debug" => "ignore",
            "test" => "Hello World",
            "x" => ["x", "y", 5],
            "z" => ["ha" => ["hu" => "LEEEEEEEEEEEEEL"]]
        ];
        $engine = new engine();
        return $engine->mainThreadRender($content, $dataArray);
        $end_exec_time = round(microtime(true) *1000) - $start_exec_time;
        dd($end_exec_time);
    }


    public function showPageFromTemplate($pathToTemplate, $dataArray = [], $renderEngine = "pp")
    {
        $realPathToTemplate = $this->parseTemplatePath($pathToTemplate);
        if(strtolower($renderEngine) == "pp")
        {
            return $this->renderTemplateWithPP($realPathToTemplate, $dataArray);  // [Default]  rendering with PineapplePen.
        }
        else
        {
            return $this->renderTemplateWithBlade($realPathToTemplate, $dataArray);
        }
    }

    public function renderTemplateWithPP($realPathToTemplate, $dataArray)
    {
        return $this->readTemplateFileByPath($realPathToTemplate);
    }

    public function renderTemplateWithBlade($realPathToTemplate, $dataArray)
    {
        //unsupportet
    }


    public function parseTemplatePath($pathToTemplate)  // [Helper Function]  Parse the Template.folder.filename to \templat\folder\filename for exmple.
    {
        $realPathToTemplate = str_replace(".", "\\", $pathToTemplate);
        return __DIR__ . "\\". $realPathToTemplate . ".tem.html"; // [return] retruns the parsed Path off requested Template file.
    }

    public function readTemplateFileByPath($realPathToTemplate)  // [Helper Function]  Parse the Template.folder.filename to \templat\folder\filename for exmple.
    {
        $fileAsString = File::get($realPathToTemplate); // [read] file;
        return $fileAsString;  // [Return]  the file as string.
    }
}
