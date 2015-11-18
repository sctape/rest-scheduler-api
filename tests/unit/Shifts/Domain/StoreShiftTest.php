<?php namespace Scheduler\Codeception\Unit\Shifts\Domain;

use Codeception\TestCase\Test;
use League\Fractal\Manager;
use League\Tactician\CommandBus;
use Mockery;
use Scheduler\Shifts\Contracts\Shift;
use Scheduler\Shifts\Domain\StoreShift;
use Spark\Adr\PayloadInterface;

/**
 * Class StoreShiftTest
 * @package Scheduler\Codeception\Unit\Shifts\Domain
 * @author Sam Tape <sctape@gmail.com>
 */
class StoreShiftTest extends Test
{
    public function testInvoke()
    {
//        $shift = mockery::mock(Shift::class);
//        $shift->shouldReceive('getId')->once()->withNoArgs()->andReturn(1);
//        $shift->shouldReceive('getId')->once()->withNoArgs()->andReturn(1);
//        $shift->shouldReceive('getId')->once()->withNoArgs()->andReturn(1);
//
//
//        $payloadInterface = mockery::mock(PayloadInterface::class);
//        $commandBus = mockery::mock(CommandBus::class);
//        $commandBus->shouldReceive('handle')->once()->andReturn($shift);
//        $fractal = mockery::mock(Manager::class);
//
//        $storeShiftDomain = new StoreShift($payloadInterface, $commandBus, $fractal);
//
//        $input = [
//            'manager_id' => 1,
//            'employee_id' => 2,
//            'break' => 15,
//            'start_time' => '2015-12-01 00:00:00',
//            'end_time' => '2015-12-02 00:00:00',
//        ];
//
//        $payload = $storeShiftDomain->__invoke($input);
    }
}