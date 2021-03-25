<?php

namespace Tests;

use Mockery as m;
use App\Mobile;
use App\Interfaces\CarrierInterface;

use PHPUnit\Framework\TestCase;

class MobileTest extends TestCase
{
	
	/** @test */
	public function it_returns_null_when_name_empty()
	{
		$provider = m::mock(CarrierInterface::class);

		$mobile = new Mobile($provider);

		$this->assertNull($mobile->makeCallByName(''));
	}

}
