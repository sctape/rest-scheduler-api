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
use Scheduler\Users\Domain\GetUserShifts;
use Scheduler\Users\Repository\UserRepository;
use Spark\Adr\PayloadInterface;
use Spark\Auth\AuthHandler;
use Spark\Auth\Token;

/**
 * Class GetUserShiftsTest
 * @package Scheduler\Codeception\Unit\Users\Domain
 * @author Sam Tape <sctape@gmail.com>
 */
class GetUserShiftsTest extends Test
{
    /**
     * Test that the GetUserShifts domain can take in input with an ID and auth user, and return an array
     */
    public function testInvoke()
    {
        $shift = mockery::mock(Shift::class);
        $shifts = [$shift];

        $employee = mockery::mock(User::class);

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

        $fractal = mockery::mock(Manager::class);
        $fractal->shouldReceive('parseIncludes')->once()->with('employee,manager')->andReturnSelf();
        $fractal->shouldReceive('createData')->once()->with($collection)->andReturn($scope);

        $token = mockery::mock(Token::class);
        $token->shouldReceive('getMetadata')->once()->with('id')->andReturn(1);

        $domain = new GetUserShifts($payloadInterface, $userRepository, $shiftRepository, $fractal, $shiftTransformer, $collection);

        $input = [
            'id' => 1,
            'include' => 'employee,manager',
            AuthHandler::TOKEN_ATTRIBUTE => $token,
        ];

        $payload = $domain->__invoke($input);
        $this->assertInstanceOf(PayloadInterface::class, $payload);
    }
}