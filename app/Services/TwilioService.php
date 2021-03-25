<?php

namespace App\Services;

use App\Contact;
use App\SMS;
use GuzzleHttp\Client as Guzzle;


class TwilioService
{
	
	public static function sendAndTrackSMS($number, $message)
	{
		$client = new Guzzle([
            'base_uri' => "http://demo1469828.mockable.io/"
        ]);

        $response = $client->request('POST', 'send-sms', ['json' => ['number' => $number, 'message' => $message]]);

        $response = $response->getBody();
        $response = json_decode($response->getContents(), true);

        $sms = new SMS();
        $sms->code = $response['code'];
        $sms->details = $response['msg'];

        return $sms;
	}

}