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

//test
use App\helpers;

class paymentController extends Controller
{
    //constructor
    public function __construct()
    {
        //$this->middleware("auth"); //auth
        $this->middleware('LanguageMiddleware'); //lang
    }

    public function paymentDone($payID, $id)
    {

        if(strtolower( $id ) == "me") //id reprensdnts the id of the user
        {
            $id = Auth()->user()->id; //get user id
        }

        $check_zahlung = Paymentslists::where("payID", $payID)->where("user", $id)->count(); //check if Payment exists
        if($check_zahlung <= 0)
        {
            $customError = "Error: Invalid Payment";
            return view("errors.customError", ["err" => $customError]); //return error if not exists
        }
        else
        {
            $zahlung = Paymentslists::where("payID", $payID)->where("user", $id)->first(); //get Payment
            if($zahlung["status"] == 3) //zahlung erfolgreich
            {
                return response("Es wurden ".$zahlung["price"]." EUR auf ihr Konto eingezahlt.");
            }
            return response("Leider war die zahlung nicht erfolgreich :/"); //error
        }

    }



    public function paymentMake($method, $id, $price)
    {
        if(strtolower( $id ) == "me") //id reprensdnts the id of the user
        {
            $id = Auth()->user()->id; //get user id
        }

        if(PaymentPlugins::where("MethodName", strtolower($method))->count() <= 0) //check if Payment-method exists in table;
        {
            return view("errors.paymentMethodNotFound"); //error return;
        }

        $price = number_format($price, 2);

        if(round($price, 2) < 1 && round($price, 2) > 0) //parse the price (min >= 1, or 0);
        {
            $customError = "Error: Invalid Amount ::".number_format($price, 2);
            return view("errors.customError", ["err" => $customError]); //return error
        }

        $payMethod = PaymentPlugins::where("methodName", strtolower($method))->first(); //get Payment method plugin classname
        $pluginame = $payMethod["methodUrl"]; //represwentiert den klassennamen / pluginnamen
        $plugin = "App\Plugins\payment\\".$pluginame."\handler"; //dynamicly call
        $payment = new $plugin(); //create class instace

        $payID = Paymentslists::where("payID", "STORAGE_NUMBER")->first(); //generate Payment ID
        $payID = $payID["status"] + 1; // [see last line]
        Paymentslists::where("payID", "STORAGE_NUMBER")->update(["status" => $payID]); //update
        Paymentslists::insert(["price" => round($price, 2), "user" => $id, "payID" => $payID, "status" => "1", "extra" => $method]); //create the Payment in Database
        $array = [
            "currency" => "EUR",
            "price" => $price,
            "description" => "Payment: #".$payID, //edit
            "payID" => $payID,
            "resultUrl" => "http://84.179.115.208/whm/remake/public/Payment/done/".$payID."/".$id
        ]; // represents the required data for the Payment plugin / provider

         $result = $payment->callDirect('*sampleController@makePayment', $array); //call the makePayment function in Payment plugin
       // $dat = array("*sampleController@makePayment", $array);
        //$result = call_user_func_array(array($Payment, "callDirect"), $dat);
        if(explode("||",$result)[0] == "err")
        {
            $customError = "ERROR:" . explode("||",$result)[1];
            return view("errors.customError", ["err" => $customError]);
        }
        return redirect(explode("||",$result)[1], 303); //return the url from plugin
    }
}
