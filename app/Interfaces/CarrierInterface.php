<?php

namespace App\Interfaces;

use App\Contact;
use App\Call;
use App\SMS;


interface CarrierInterface
{
	
	public function dialContact(Contact $contact);

	public function makeCall(): Call;

	public function sendSMS(string $number, string $body): SMS;
}