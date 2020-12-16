<?php
namespace App\Plugins\Payment\MolliePlugin\Main\Controllers;

use App\models\paymentslists;
use app\Plugins\payment\MolliePlugin\Main\internal\render;
use Illuminate\Http\Request;
use Mollie\Laravel\Facades\Mollie;  // [USE]  Mollie Laravel Github Package.

class MollieController
{
    public function makePayment($array) // [plugin] Function to call by the paymentController->MolliePlugin->handler .
    {
        $currency = $array["currency"];
        $price = $array["price"];
        $result = $array["resultUrl"];
        $description = $array["description"] ;
        $payID = $array["payID"];
        $payment = Mollie::api()->payments->create([
            "amount" => [
                "currency" => $currency,
                "value" => $price // You must send the correct number of decimals, thus we enforce the use of strings
            ],
            "description" => $description,
            "redirectUrl" => $result,
            "webhookUrl" => "https://84.179.115.208/whm/remake/public/Payment/api/status/mollie/post",
            "metadata" => [
                "payID" => $payID
            ],
        ]);

        // redirect customer to Mollie checkout page
        return "redirect||" . $payment->getCheckoutUrl();
    }

    public function sendStatus($request)
    {
        $paymentId = $request->input('id');
        $payment = Mollie::api()->payments->get($paymentId);
        $payID = $payment->metadata->payID;
        if ($payment->isPaid())
        {
            Paymentslists::where("payID", "!=", "STORAGE_NUMBER")->where("payID", $payID)->update(["status" => "3"]); //status: 3 == Bezahlt.
            // [todo] add money to user account
        }
        else
        {
            Paymentslists::where("payID", $payID)->update(["status" => "2"]); // status: 2 == Nicht Bezahlt
        }

        return response("Thanks!");
    }
}
