<?php

namespace App\Http\Controllers\PaymentControllers;
use DB;
use Session;
Use App;
use Config;
use Artisan;
use File;
use Hash;

use App\User;
use App\Models\PaymentPlugins;
use App\Models\Paymentslists; //alle bezahlungen

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use Mollie\Laravel\Facades\Mollie;

class PaymentAPIController extends Controller
{
    //constructor
    public function __construct()
    {
        //
    }

    //get status handshake from payment provider (optionally) [api]
    public function sendStatus($method, Request $request)
    {
        $payMethod = PaymentPlugins::where("methodName", strtolower($method))->first(); //get payment method plugin classname
        $pluginame = $payMethod["methodUrl"]; //represwentiert den klassennamen / pluginnamen
        $plugin = "App\Plugins\payment\\".$pluginame."\handler"; //dynamicly call
        $payment = new $plugin(); //create class instace

        $result = $payment->callDirect('*sampleController@sendStatus', $request);
        return response("Thanks!");
    }
}
