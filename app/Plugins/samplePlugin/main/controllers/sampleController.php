<?php
namespace App\Plugins\samplePlugin\Main\Controllers;

use App\models\paymentslists;
use App\Plugins\samplePlugin\Main\internal\render;


class sampleController
{
    public function showTime()
    {
        $render = new render();
        return $render->view("index", ["title" => "hello"]);
    }
}
