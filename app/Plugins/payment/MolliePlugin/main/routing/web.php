<?php
namespace App\Plugins\Native\Main\Routing;

class web { public function webRoutes() {
    //Here come your routes wich are pointing to /user/flow and /user/send
    return [

        "/index/:PARAM" => "*myTest@test",
        "/form" => "*myTest@form",
        "/form/post" => "*myTest@post"

    ];

}}