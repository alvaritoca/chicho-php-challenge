<?php

namespace App;

use App\Interfaces\CarrierInterface;
use App\Services\ContactService;


class Mobile
{

	protected $provider;
	
	function __construct(CarrierInterface $provider)
	{
		$this->provider = $provider;
	}


	public function makeCallByName($name = '')
	{
		if( empty($name) ) return;

		$contact = ContactService::findByName($name);

		if(!isset($contact)) {
			throw new \Exception("No contact was found for the given name.");
		}

		$this->provider->dialContact($contact);

		return $this->provider->makeCall();
	}

	public function sendSMS($number = null, $body = null)
	{
		if( !isset($number) || !isset($body) ) {
			throw new \Exception("A phone number and the message body are required to send an SMS.");
		}

		$isValidNumber = ContactService::validateNumber($number);

		if (!$isValidNumber) {
			throw new \InvalidArgumentException("The phone number is invalid.");
		}

		return $this->provider->sendSMS($number, $body);
	}


}
