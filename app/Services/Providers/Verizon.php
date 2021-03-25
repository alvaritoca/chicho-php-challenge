<?php

namespace App\Services\Providers;

use App\Contact;
use App\Interfaces\CarrierInterface;

class Verizon implements CarrierInterface
{
    public function dialContact(Contact $contact)
    {
        //dialContact for Verizon
    }

    public function makeCall()
    {
        //make a call for Verizon
    }

    public function sendSMS(string $number, string $body)
    {
        //send sms for Verizon
    }
}