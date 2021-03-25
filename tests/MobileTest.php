<?php

namespace Tests;

use Mockery as m;
use App\Mobile;
use App\Call;
use App\Contact;
use App\Services\ContactService;
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

	/** @test */
	public function it_returns_a_call_instance_when_calling_by_name()
	{
		$call = m::mock('overload:'.Call::class);
		$contact = m::mock('overload:'.Contact::class);
		$contact->name = "Jose Bejarano";
		$contact->number = "948562369";

		$provider = m::mock(CarrierInterface::class);
		$provider->shouldReceive('dialContact')
				->withArgs([$contact]);

		$provider->shouldReceive('makeCall')
				->andReturn($call);

		m::mock('alias:'.ContactService::class)
			->shouldReceive('findByName')
			->withArgs(['Jose Bejarano'])
			->andReturn($contact);
		
		$mobile = new Mobile($provider);

		$this->assertInstanceOf(Call::class, $mobile->makeCallByName('Jose Bejarano'));
	}

	/** @test */
	public function it_throws_an_exception_when_a_contact_was_not_found()
	{
		$call = m::mock('overload:'.Call::class);
		$provider = m::mock(CarrierInterface::class);

		m::mock('alias:'.ContactService::class)
			->shouldReceive('findByName')
			->withArgs(['Jose Bejarano'])
			->andReturn(null);
		
		$this->expectException(\Exception::class);

		$mobile = new Mobile($provider);
		$mobile->makeCallByName('Jose Bejarano');
	}

}
