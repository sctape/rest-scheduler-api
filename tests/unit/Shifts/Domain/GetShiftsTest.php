<?php namespace Scheduler\Codeception\Unit\Shifts\Domain;

use BeatSwitch\Lock\Callers\Caller;
use Codeception\TestCase\Test;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Scope;
use Mockery;
use Scheduler\Exception\UserNotAuthorized;
use Scheduler\Shifts\Contracts\Shift;
use Scheduler\Shifts\Domain\GetShifts;
use Scheduler\Shifts\Repository\ShiftRepository;
use Scheduler\Shifts\Transformer\ShiftTransformer;
use Spark\Adr\PayloadInterface;
use Spark\Auth\AuthHandler;
use Spark\Auth\Token;

/**
 * Class GetShiftsTest
 * @package Scheduler\Codeception\Unit\Shifts\Domain
 * @author Sam Tape <sctape@gmail.com>
 */
class GetShiftsTest extends Test
{
    public function testInvoke()
    {
        $shift = mockery::mock(Shift::class);
        $shifts = [$shift];

        $user = mockery::mock(Caller::class);
        $user->shouldReceive('getId')->once()->withNoArgs()->andReturn(1);

        $lockManager = mockery::mock(\BeatSwitch\Lock\Manager::class);
        $shiftTransformer = mockery::mock(ShiftTransformer::class);

        $scope = mockery::mock(Scope::class);
        $scope->shouldReceive('toArray')->withNoArgs()->andReturn([]);

        $collection = mockery::mock(Collection::class);
        $collection->shouldReceive('setData')->once()->with($shifts)->andReturnSelf();
        $collection->shouldReceive('setTransformer')->once()->with($shiftTransformer)->andReturnSelf();

        $payloadInterface = mockery::mock(PayloadInterface::class);
        $payloadInterface->shouldReceive('withStatus')->once()->with(PayloadInterface::OK)->andReturnSelf();
        $payloadInterface->shouldReceive('withOutput')->once()->with([])->andReturnSelf();

        $shiftRepository = mockery::mock(ShiftRepository::class);
        $shiftRepository->shouldReceive('getShiftsBetween')->once()->andReturn($shifts);

        $fractal = mockery::mock(Manager::class);
        $fractal->shouldReceive('createData')->once()->with($collection)->andReturn($scope);
        $fractal->shouldReceive('parseIncludes')->once()->with(['manager','employee'])->andReturnSelf();

        $token = mockery::mock(Token::class);
        $token->shouldReceive('getMetadata')->once()->with('entity')->andReturn($user);

        $domain = mockery::mock(GetShifts::class . '[authorizeUser]', [$payloadInterface, $shiftRepository, $fractal, $lockManager, $shiftTransformer, $collection]);
        $domain->shouldReceive('authorizeUser')->once()->with($user, 'view', 'shifts')->andReturnNull();

        $input = [
            'startDateTime' => '2015-12-01 00:00:00',
            'endDateTime' => '2015-12-02 00:00:00',
            AuthHandler::TOKEN_ATTRIBUTE => $token,
        ];

        $payload = $domain->__invoke($input);
        $this->assertInstanceOf(PayloadInterface::class, $payload);
    }

    /**
     * @expectedException \Scheduler\Exception\UserNotAuthorized
     */
    public function testInvokeWithUnauthorizedUser()
    {
        $shift = mockery::mock(Shift::class);
        $shifts = [$shift];

        $user = mockery::mock(Caller::class);
        $user->shouldReceive('getId')->once()->withNoArgs()->andReturn(1);

        $lockManager = mockery::mock(\BeatSwitch\Lock\Manager::class);
        $shiftTransformer = mockery::mock(ShiftTransformer::class);

        $scope = mockery::mock(Scope::class);
        $scope->shouldReceive('toArray')->withNoArgs()->andReturn([]);

        $collection = mockery::mock(Collection::class);
        $collection->shouldReceive('setData')->once()->with($shifts)->andReturnSelf();
        $collection->shouldReceive('setTransformer')->once()->with($shiftTransformer)->andReturnSelf();

        $payloadInterface = mockery::mock(PayloadInterface::class);
        $payloadInterface->shouldReceive('withStatus')->once()->with(PayloadInterface::OK)->andReturnSelf();
        $payloadInterface->shouldReceive('withOutput')->once()->with([])->andReturnSelf();

        $shiftRepository = mockery::mock(ShiftRepository::class);
        $shiftRepository->shouldReceive('getShiftsBetween')->once()->andReturn($shifts);

        $fractal = mockery::mock(Manager::class);
        $fractal->shouldReceive('createData')->once()->with($collection)->andReturn($scope);
        $fractal->shouldReceive('parseIncludes')->once()->with(['manager','employee'])->andReturnSelf();

        $token = mockery::mock(Token::class);
        $token->shouldReceive('getMetadata')->once()->with('entity')->andReturn($user);

        $domain = mockery::mock(GetShifts::class . '[authorizeUser]', [$payloadInterface, $shiftRepository, $fractal, $lockManager, $shiftTransformer, $collection]);
        $domain->shouldReceive('authorizeUser')->once()->with($user, 'view', 'shifts')->andThrow(new UserNotAuthorized);

        $input = [
            'startDateTime' => '2015-12-01 00:00:00',
            'endDateTime' => '2015-12-02 00:00:00',
            AuthHandler::TOKEN_ATTRIBUTE => $token,
        ];

        $payload = $domain->__invoke($input);
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
        $shifts = [$shift];

        $user = mockery::mock(Caller::class);
        $user->shouldReceive('getId')->once()->withNoArgs()->andReturn(1);

        $lockManager = mockery::mock(\BeatSwitch\Lock\Manager::class);
        $shiftTransformer = mockery::mock(ShiftTransformer::class);

        $scope = mockery::mock(Scope::class);
        $scope->shouldReceive('toArray')->withNoArgs()->andReturn([]);

        $collection = mockery::mock(Collection::class);
        $collection->shouldReceive('setData')->once()->with($shifts)->andReturnSelf();
        $collection->shouldReceive('setTransformer')->once()->with($shiftTransformer)->andReturnSelf();

        $payloadInterface = mockery::mock(PayloadInterface::class);
        $payloadInterface->shouldReceive('withStatus')->once()->with(PayloadInterface::OK)->andReturnSelf();
        $payloadInterface->shouldReceive('withOutput')->once()->with([])->andReturnSelf();

        $shiftRepository = mockery::mock(ShiftRepository::class);
        $shiftRepository->shouldReceive('getShiftsBetween')->once()->andReturn($shifts);

        $fractal = mockery::mock(Manager::class);
        $fractal->shouldReceive('createData')->once()->with($collection)->andReturn($scope);
        $fractal->shouldReceive('parseIncludes')->once()->with(['manager','employee'])->andReturnSelf();

        $token = mockery::mock(Token::class);
        $token->shouldReceive('getMetadata')->once()->with('entity')->andReturn($user);

        $domain = mockery::mock(GetShifts::class . '[authorizeUser]', [$payloadInterface, $shiftRepository, $fractal, $lockManager, $shiftTransformer, $collection]);
        $domain->shouldReceive('authorizeUser')->once()->with($user, 'view', 'shifts')->andReturnNull();

        $input = [
            AuthHandler::TOKEN_ATTRIBUTE => $token,
        ];

        $payload = $domain->__invoke($input);
        $this->assertInstanceOf(PayloadInterface::class, $payload);
    }
}