<?php

namespace Tests;

use Mockery as m;
use App\Mobile;
use App\Call;
use App\Contact;
use App\SMS;
use App\Services\ContactService;
use App\Interfaces\CarrierInterface;

use PHPUnit\Framework\TestCase;

/**
 * @runTestsInSeparateProcesses
 */
class MobileTest extends TestCase
{
	protected $provider;

	protected function setUp(): void
	{
		parent::setUp();

		$this->provider = m::mock(CarrierInterface::class);
	}
	
	/** @test */
	public function it_returns_null_when_name_empty()
	{
		$mobile = new Mobile($this->provider);

		$this->assertNull($mobile->makeCallByName(''));
	}

	/** @test */
	public function it_returns_a_call_instance_when_calling_by_name()
	{
		$call = m::mock('overload:'.Call::class);

		$contact = m::mock('overload:'.Contact::class);
		$contact->name = "Jose Bejarano";
		$contact->number = "948562369";

		$this->provider->shouldReceive('dialContact')
			->withArgs([$contact]);

		$this->provider->shouldReceive('makeCall')
			->andReturn($call);

		m::mock('alias:'.ContactService::class)
			->shouldReceive('findByName')
			->withArgs(['Jose Bejarano'])
			->andReturn($contact);
		
		$mobile = new Mobile($this->provider);

		$this->assertInstanceOf(Call::class, $mobile->makeCallByName('Jose Bejarano'));
	}

	/** @test */
	public function it_throws_an_exception_when_a_contact_was_not_found()
	{
		$call = m::mock('overload:'.Call::class);

		m::mock('alias:'.ContactService::class)
			->shouldReceive('findByName')
			->withArgs(['Jose Bejarano'])
			->andReturn(null);
		
		$this->expectException(\Exception::class);

		$mobile = new Mobile($this->provider);
		$mobile->makeCallByName('Jose Bejarano');
	}

	/** @test */
	public function it_should_send_an_sms_to_the_given_number()
	{
		$sms = m::mock('overload:'.SMS::class);

		$this->provider->shouldReceive('sendSMS')
			->withArgs(['(948)787-6532', 'This is a test message!'])
			->andReturn($sms);

		m::mock('alias:'.ContactService::class)
			->shouldReceive('validateNumber')
			->withArgs(['(948)787-6532'])
			->andReturn(true);

		$mobile = new Mobile($this->provider);

		$this->assertInstanceOf(SMS::class, $mobile->sendSMS('(948)787-6532', 'This is a test message!'));
	}

	/** @test */
	public function it_throws_an_exception_when_the_number_is_invalid()
	{
		$sms = m::mock('overload:'.SMS::class);

		m::mock('alias:'.ContactService::class)
			->shouldReceive('validateNumber')
			->withArgs(['999'])
			->andReturn(false);

		$this->expectException(\InvalidArgumentException::class);

		$mobile = new Mobile($this->provider);
		$mobile->sendSMS('999', 'This is a test message!');
	}

	/** @test */
	public function it_throws_an_exception_when_the_arguments_are_missing()
	{
		$sms = m::mock('overload:'.SMS::class);

		m::mock('alias:'.ContactService::class)
			->shouldReceive('validateNumber')
			->withArgs(['999'])
			->andReturn(false);

		$this->expectException(\Exception::class);

		$mobile = new Mobile($this->provider);
		$mobile->sendSMS();
	}

}
