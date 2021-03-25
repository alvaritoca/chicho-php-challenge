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


}
