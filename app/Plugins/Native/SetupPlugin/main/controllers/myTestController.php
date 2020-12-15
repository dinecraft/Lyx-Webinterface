<?php
namespace App\Plugins\Native\SetupPlugin\Main\Controllers;

use App\Plugins\Native\SetupPlugin\Main\Internal\render;


class myTestController
{
    public function test($request, $params)
    {
        $r = new render();
        $result = $r->view("index", ["title" => $params[0]]);
        return $result;
    }

    public function form($request, $params)
    {
        $r = new render();
        $result = $r->view("form", ["title" => "Formular"]);
        return $result;
    }

    public function post($request, $params)
    {
        $result = "Erfolgreich gesendet => | " . $request->input("ja");
        return $result;
    }
}