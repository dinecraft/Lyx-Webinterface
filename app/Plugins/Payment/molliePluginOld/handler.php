<?php
namespace App\Plugins\payment\molliePluginOld;

//class bindings
use Mollie\Laravel\Facades\Mollie;


use DB;

class handler
{

    public function makePayment($array) // [plugin] Function to call by the  .
    {
        $price = $array["price"] ;
        $result = $array["resultUrl"];
        $description = $array["description"] ;
        $payID = $array["payID"];
        $payment = Mollie::api()->payments->create([
            "amount" => [
                "currency" => "EUR",
                "value" => $price // You must send the correct number of decimals, thus we enforce the use of strings
            ],
            "description" => $description,
            "redirectUrl" => $result,
            "webhookUrl" => "https://84.179.115.208/whm/remake/public/Payment/api/status/post",
            "metadata" => [
                "payID" => $payID
            ],
        ]);

        // redirect customer to Mollie checkout page
        return $payment->getCheckoutUrl();
    }

    /**
     * After the customer has completed the transaction,
     * you can fetch, check and process the Payment.
     * This logic typically goes into the controller handling the inbound webhook request.
     * See the webhook docs in /docs and on mollie.com for more information.
     */
    public function handleWebhookNotification() {
       // $paymentId = $request->input('id');
        $payment = Mollie::api()->payments->get("tr_KGypS6FmEE");

        if ($payment->isPaid())
        {
            DB::table("payments")->insert(["methodName" => "juhu", "methodUrl" => "am besten"]);
        }
        else
        {
            DB::table("payments")->insert(["methodName" => "kek", "methodUrl" => "cool"]);
        }
        return response("test");
    }
}
