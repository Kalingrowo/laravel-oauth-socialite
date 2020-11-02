<?php

namespace App\Http\Controllers\Twilio;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Twilio\Rest\Client;

class TwilioController extends Controller
{
    public function sendWhatsappMessages()
    {
        try {
            $twilioSID = env('TWILIO_ACCOUNT_SID');
            $twilioToken = env('TWILIO_AUTH_TOKEN');

            $twilio = new Client($twilioSID, $twilioToken);

            // $sendMessage = $twilio->messages
            //     ->create("whatsapp:+6285331092940", [
            //         "from" => "whatsapp:+14155238886",
            //         "body" => "Oit pak !"
            //     ]);

            return redirect()->back();
        } catch (\Throwable $th) {
            dd($th->getMessage());
        }
    }
}
