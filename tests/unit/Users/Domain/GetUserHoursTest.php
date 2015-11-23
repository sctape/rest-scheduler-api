<?php namespace Scheduler\Codeception\Unit\Users\Domain;

use Codeception\TestCase\Test;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Scope;
use Mockery;
use Scheduler\Shifts\Repository\ShiftRepository;
use Scheduler\Shifts\Transformer\HoursTransformer;
use Scheduler\Users\Contracts\User;
use Scheduler\Users\Domain\GetUserHours;
use Scheduler\Users\Repository\UserRepository;
use Spark\Adr\PayloadInterface;
use Spark\Auth\AuthHandler;
use Spark\Auth\Token;

/**
 * Class GetUserHoursTest
 * @package Scheduler\Codeception\Unit\Users\Domain
 * @author Sam Tape <sctape@gmail.com>
 */
class GetUserHoursTest extends Test
{
    /**
     * Test that the GetUserHours domain can take in input with an ID and auth user, and return an array
     */
    public function testInvoke()
    {
        $hours = [
            [
                'week_start' => '2015-01-01 00:00:00',
                'week_end' => '2015-01-07 23:59:59',
                'hours_count' => 2,
            ]
        ];

        $employee = mockery::mock(User::class);

        $hoursTransformer = mockery::mock(HoursTransformer::class);

        $scope = mockery::mock(Scope::class);
        $scope->shouldReceive('toArray')->withNoArgs()->andReturn([]);

        $collection = mockery::mock(Collection::class);
        $collection->shouldReceive('setData')->once()->with($hours)->andReturnSelf();
        $collection->shouldReceive('setTransformer')->once()->with($hoursTransformer)->andReturnSelf();

        $payloadInterface = mockery::mock(PayloadInterface::class);
        $payloadInterface->shouldReceive('withStatus')->once()->with(PayloadInterface::OK)->andReturnSelf();
        $payloadInterface->shouldReceive('withOutput')->once()->with([])->andReturnSelf();

        $shiftRepository = mockery::mock(ShiftRepository::class);
        $shiftRepository->shouldReceive('getHoursCountGroupedByWeekFor')->once()->with($employee)->andReturn($hours);

        $userRepository = mockery::mock(UserRepository::class);
        $userRepository->shouldReceive('getOneByIdOrFail')->once()->with(1)->andReturn($employee);

        $fractal = mockery::mock(Manager::class);
        $fractal->shouldReceive('createData')->once()->with($collection)->andReturn($scope);

        $token = mockery::mock(Token::class);
        $token->shouldReceive('getMetadata')->once()->with('id')->andReturn(1);

        $domain = new GetUserHours($payloadInterface, $shiftRepository, $userRepository, $fractal, $hoursTransformer, $collection);

        $input = [
            'id' => 1,
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
        $hours = [
            [
                'week_start' => '2015-01-01 00:00:00',
                'week_end' => '2015-01-07 23:59:59',
                'hours_count' => 2,
            ]
        ];

        $employee = mockery::mock(User::class);

        $hoursTransformer = mockery::mock(HoursTransformer::class);

        $scope = mockery::mock(Scope::class);
        $scope->shouldReceive('toArray')->withNoArgs()->andReturn([]);

        $collection = mockery::mock(Collection::class);
        $collection->shouldReceive('setData')->once()->with($hours)->andReturnSelf();
        $collection->shouldReceive('setTransformer')->once()->with($hoursTransformer)->andReturnSelf();

        $payloadInterface = mockery::mock(PayloadInterface::class);
        $payloadInterface->shouldReceive('withStatus')->once()->with(PayloadInterface::OK)->andReturnSelf();
        $payloadInterface->shouldReceive('withOutput')->once()->with([])->andReturnSelf();

        $shiftRepository = mockery::mock(ShiftRepository::class);
        $shiftRepository->shouldReceive('getHoursCountGroupedByWeekFor')->once()->with($employee)->andReturn($hours);

        $userRepository = mockery::mock(UserRepository::class);
        $userRepository->shouldReceive('getOneByIdOrFail')->once()->with(1)->andReturn($employee);

        $fractal = mockery::mock(Manager::class);
        $fractal->shouldReceive('createData')->once()->with($collection)->andReturn($scope);

        $token = mockery::mock(Token::class);
        $token->shouldReceive('getMetadata')->once()->with('id')->andReturn(1);

        $domain = new GetUserHours($payloadInterface, $shiftRepository, $userRepository, $fractal, $hoursTransformer, $collection);

        $input = [
            'id' => 2,
            AuthHandler::TOKEN_ATTRIBUTE => $token,
        ];

        $payload = $domain->__invoke($input);
        $this->assertInstanceOf(PayloadInterface::class, $payload);
    }
}