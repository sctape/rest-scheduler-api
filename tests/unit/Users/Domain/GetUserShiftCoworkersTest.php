<?php namespace Scheduler\Codeception\Unit\Users\Domain;

use Codeception\TestCase\Test;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Scope;
use Mockery;
use Scheduler\Shifts\Contracts\Shift;
use Scheduler\Shifts\Repository\ShiftRepository;
use Scheduler\Shifts\Transformer\ShiftTransformer;
use Scheduler\Users\Contracts\User;
use Scheduler\Users\Domain\GetUserShiftCoworkers;
use Scheduler\Users\Domain\GetUserShifts;
use Scheduler\Users\Repository\UserRepository;
use Spark\Adr\PayloadInterface;
use Spark\Auth\AuthHandler;
use Spark\Auth\Token;

/**
 * Class GetUserShiftCoworkersTest
 * @package Scheduler\Codeception\Unit\Users\Domain
 * @author Sam Tape <sctape@gmail.com>
 */
class GetUserShiftCoworkersTest extends Test
{
    /**
     * Test that the GetUserCoworkers domain can take in input with an ID and auth user, and return an array
     */
    public function testInvoke()
    {
        $employee = mockery::mock(User::class);
        $employee2 = mockery::mock(User::class);
        $coworkers = [$employee2];

        $startTime = new \DateTime();
        $endTime = new \DateTime("+2 hours");

        $shift = mockery::mock(Shift::class);
        $shift->shouldReceive('setCoworkers')->once()->with($coworkers)->andReturnNull();
        $shift->shouldReceive('getStartTime')->once()->withNoArgs()->andReturn($startTime);
        $shift->shouldReceive('getEndTime')->once()->withNoArgs()->andReturn($endTime);
        $shifts = [$shift];

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
        $shiftRepository->shouldReceive('getByEmployee')->once()->with($employee)->andReturn($shifts);

        $userRepository = mockery::mock(UserRepository::class);
        $userRepository->shouldReceive('getOneByIdOrFail')->once()->with(1)->andReturn($employee);
        $userRepository->shouldReceive('getEmployeesWorkingBetween')->once()->with($startTime, $endTime, [$employee])->andReturn($coworkers);

        $fractal = mockery::mock(Manager::class);
        $fractal->shouldReceive('createData')->once()->with($collection)->andReturn($scope);
        $fractal->shouldReceive('parseIncludes')->once()->with('coworkers')->andReturnSelf();

        $token = mockery::mock(Token::class);
        $token->shouldReceive('getMetadata')->once()->with('id')->andReturn(1);

        $domain = new GetUserShiftCoworkers($payloadInterface, $shiftRepository, $userRepository, $fractal, $shiftTransformer, $collection);

        $input = [
            'id' => 1,
            'include' => 'employee,manager',
            AuthHandler::TOKEN_ATTRIBUTE => $token,
        ];

        $payload = $domain->__invoke($input);
        $this->assertInstanceOf(PayloadInterface::class, $payload);
    }

    /**
     * Test that an exception is thrown if the auth user does not match the requested user
     *
     * @expectedException \Scheduler\Exception\UserNotAuthorized
     */
    public function testInvokeWithUnauthorizedUser()
    {
        $employee = mockery::mock(User::class);
        $employee2 = mockery::mock(User::class);
        $coworkers = [$employee2];

        $startTime = new \DateTime();
        $endTime = new \DateTime("+2 hours");

        $shift = mockery::mock(Shift::class);
        $shift->shouldReceive('setCoworkers')->once()->with($coworkers)->andReturnNull();
        $shift->shouldReceive('getStartTime')->once()->withNoArgs()->andReturn($startTime);
        $shift->shouldReceive('getEndTime')->once()->withNoArgs()->andReturn($endTime);
        $shifts = [$shift];

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
        $shiftRepository->shouldReceive('getByEmployee')->once()->with($employee)->andReturn($shifts);

        $userRepository = mockery::mock(UserRepository::class);
        $userRepository->shouldReceive('getOneByIdOrFail')->once()->with(1)->andReturn($employee);
        $userRepository->shouldReceive('getEmployeesWorkingBetween')->once()->with($startTime, $endTime, [$employee])->andReturn($coworkers);

        $fractal = mockery::mock(Manager::class);
        $fractal->shouldReceive('createData')->once()->with($collection)->andReturn($scope);
        $fractal->shouldReceive('parseIncludes')->once()->with('coworkers')->andReturnSelf();

        $token = mockery::mock(Token::class);
        $token->shouldReceive('getMetadata')->once()->with('id')->andReturn(1);

        $domain = new GetUserShiftCoworkers($payloadInterface, $shiftRepository, $userRepository, $fractal, $shiftTransformer, $collection);

        $input = [
            'id' => 2,
            'include' => 'employee,manager',
            AuthHandler::TOKEN_ATTRIBUTE => $token,
        ];

        $payload = $domain->__invoke($input);
        $this->assertInstanceOf(PayloadInterface::class, $payload);
    }
}