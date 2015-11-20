<?php namespace Scheduler\Codeception\Unit\Shifts\Domain;

use BeatSwitch\Lock\Callers\Caller;
use Codeception\TestCase\Test;
use League\Fractal\Manager;
use League\Fractal\Resource\Item;
use League\Fractal\Scope;
use League\Tactician\CommandBus;
use Mockery;
use Scheduler\Exception\UserNotAuthorized;
use Scheduler\Shifts\Contracts\Shift;
use Scheduler\Shifts\Domain\StoreShift;
use Scheduler\Shifts\Transformer\ShiftTransformer;
use Spark\Adr\PayloadInterface;
use Spark\Auth\AuthHandler;
use Spark\Auth\Token;

/**
 * Class StoreShiftTest
 * @package Scheduler\Codeception\Unit\Shifts\Domain
 * @author Sam Tape <sctape@gmail.com>
 */
class StoreShiftTest extends Test
{
    public function testInvoke()
    {
        $shift = mockery::mock(Shift::class);
        $user = mockery::mock(Caller::class);
        $user->shouldReceive('getId')->once()->withNoArgs()->andReturn(1);

        $lockManager = mockery::mock(\BeatSwitch\Lock\Manager::class);
        $shiftTransformer = mockery::mock(ShiftTransformer::class);

        $scope = mockery::mock(Scope::class);
        $scope->shouldReceive('toArray')->withNoArgs()->andReturn([]);

        $item = mockery::mock(Item::class);
        $item->shouldReceive('setData')->once()->with($shift)->andReturnSelf();
        $item->shouldReceive('setTransformer')->once()->with($shiftTransformer)->andReturnSelf();

        $payloadInterface = mockery::mock(PayloadInterface::class);
        $payloadInterface->shouldReceive('withStatus')->once()->with(PayloadInterface::OK)->andReturnSelf();
        $payloadInterface->shouldReceive('withOutput')->once()->with([])->andReturnSelf();

        $commandBus = mockery::mock(CommandBus::class);
        $commandBus->shouldReceive('handle')->once()->andReturn($shift);

        $fractal = mockery::mock(Manager::class);
        $fractal->shouldReceive('createData')->once()->with($item)->andReturn($scope);

        $token = mockery::mock(Token::class);
        $token->shouldReceive('getMetadata')->once()->with('entity')->andReturn($user);

        $storeShiftDomain = mockery::mock(StoreShift::class . '[authorizeUser]', [$payloadInterface, $commandBus, $fractal, $lockManager, $shiftTransformer, $item]);
        $storeShiftDomain->shouldReceive('authorizeUser')->once()->with($user, 'create', 'shifts')->andReturnNull();

        $input = [
            'employee_id' => 2,
            'break' => 15,
            'start_time' => '2015-12-01 00:00:00',
            'end_time' => '2015-12-02 00:00:00',
            AuthHandler::TOKEN_ATTRIBUTE => $token,
        ];

        $payload = $storeShiftDomain->__invoke($input);
        $this->assertInstanceOf(PayloadInterface::class, $payload);
    }

    /**
     * @expectedException \Scheduler\Exception\UserNotAuthorized
     */
    public function testInvokeWithUnauthorizedUser()
    {
        $shift = mockery::mock(Shift::class);
        $user = mockery::mock(Caller::class);

        $lockManager = mockery::mock(\BeatSwitch\Lock\Manager::class);
        $shiftTransformer = mockery::mock(ShiftTransformer::class);

        $scope = mockery::mock(Scope::class);
        $scope->shouldReceive('toArray')->withNoArgs()->andReturn([]);

        $item = mockery::mock(Item::class);
        $item->shouldReceive('setData')->once()->with($shift)->andReturnSelf();
        $item->shouldReceive('setTransformer')->once()->with($shiftTransformer)->andReturnSelf();

        $payloadInterface = mockery::mock(PayloadInterface::class);
        $payloadInterface->shouldReceive('withStatus')->once()->with(PayloadInterface::OK)->andReturnSelf();
        $payloadInterface->shouldReceive('withOutput')->once()->with([])->andReturnSelf();

        $commandBus = mockery::mock(CommandBus::class);
        $commandBus->shouldReceive('handle')->once()->andReturn($shift);

        $fractal = mockery::mock(Manager::class);
        $fractal->shouldReceive('createData')->once()->with($item)->andReturn($scope);

        $token = mockery::mock(Token::class);
        $token->shouldReceive('getMetadata')->once()->with('entity')->andReturn($user);

        $storeShiftDomain = mockery::mock(StoreShift::class . '[authorizeUser]', [$payloadInterface, $commandBus, $fractal, $lockManager, $shiftTransformer, $item]);
        $storeShiftDomain->shouldReceive('authorizeUser')->once()->with($user, 'create', 'shifts')->andThrow(new UserNotAuthorized);

        $input = [
            'manager_id' => 1,
            'employee_id' => 2,
            'break' => 15,
            'start_time' => '2015-12-01 00:00:00',
            'end_time' => '2015-12-02 00:00:00',
            AuthHandler::TOKEN_ATTRIBUTE => $token,
        ];

        $payload = $storeShiftDomain->__invoke($input);
        $this->assertInstanceOf(PayloadInterface::class, $payload);
    }

    /**
     * Test that giving invalid data (end time that occurs before start time) will throw an error
     *
     * @expectedException \Respect\Validation\Exceptions\NestedValidationException
     */
    public function testInvokeWithInvalidData()
    {
        $shift = mockery::mock(Shift::class);
        $user = mockery::mock(Caller::class);

        $lockManager = mockery::mock(\BeatSwitch\Lock\Manager::class);
        $shiftTransformer = mockery::mock(ShiftTransformer::class);

        $scope = mockery::mock(Scope::class);
        $scope->shouldReceive('toArray')->withNoArgs()->andReturn([]);

        $item = mockery::mock(Item::class);
        $item->shouldReceive('setData')->once()->with($shift)->andReturnSelf();
        $item->shouldReceive('setTransformer')->once()->with($shiftTransformer)->andReturnSelf();

        $payloadInterface = mockery::mock(PayloadInterface::class);
        $payloadInterface->shouldReceive('withStatus')->once()->with(PayloadInterface::OK)->andReturnSelf();
        $payloadInterface->shouldReceive('withOutput')->once()->with([])->andReturnSelf();

        $commandBus = mockery::mock(CommandBus::class);
        $commandBus->shouldReceive('handle')->once()->andReturn($shift);

        $fractal = mockery::mock(Manager::class);
        $fractal->shouldReceive('createData')->once()->with($item)->andReturn($scope);

        $token = mockery::mock(Token::class);
        $token->shouldReceive('getMetadata')->once()->with('entity')->andReturn($user);

        $storeShiftDomain = mockery::mock(StoreShift::class . '[authorizeUser]', [$payloadInterface, $commandBus, $fractal, $lockManager, $shiftTransformer, $item]);
        $storeShiftDomain->shouldReceive('authorizeUser')->once()->with($user, 'create', 'shifts')->andReturnNull();

        $input = [
            'manager_id' => 1,
            'employee_id' => 2,
            'break' => 15,
            'start_time' => '2015-12-01 00:00:00',
            'end_time' => '2014-12-02 00:00:00',
            AuthHandler::TOKEN_ATTRIBUTE => $token,
        ];

        $payload = $storeShiftDomain->__invoke($input);
        $this->assertInstanceOf(PayloadInterface::class, $payload);
    }
}