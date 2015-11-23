<?php namespace Scheduler\Codeception\Unit\Users\Domain;

use BeatSwitch\Lock\Callers\Caller;
use Codeception\TestCase\Test;
use League\Fractal\Manager;
use League\Fractal\Resource\Item;
use League\Fractal\Scope;
use Mockery;
use Scheduler\Users\Domain\GetUsers;
use Scheduler\Users\Repository\UserRepository;
use Scheduler\Users\Transformer\UserTransformer;
use Spark\Adr\PayloadInterface;
use Spark\Auth\AuthHandler;
use Spark\Auth\Token;

/**
 * Class GetUsersTest
 * @package Scheduler\Codeception\Unit\Users\Domain
 * @author Sam Tape <sctape@gmail.com>
 */
class GetUsersTest extends Test
{
    /**
     * Test that the GetUsersTest domain can take in input with an ID and auth user, and return an array
     */
    public function testInvoke()
    {
        $user = mockery::mock(Caller::class);
        $user->shouldReceive('getId')->once()->withNoArgs()->andReturn(1);

        $lockManager = mockery::mock(\BeatSwitch\Lock\Manager::class);
        $userTransformer = mockery::mock(UserTransformer::class);

        $scope = mockery::mock(Scope::class);
        $scope->shouldReceive('toArray')->withNoArgs()->andReturn([]);

        $item = mockery::mock(Item::class);
        $item->shouldReceive('setData')->once()->with($user)->andReturnSelf();
        $item->shouldReceive('setTransformer')->once()->with($userTransformer)->andReturnSelf();

        $payloadInterface = mockery::mock(PayloadInterface::class);
        $payloadInterface->shouldReceive('withStatus')->once()->with(PayloadInterface::OK)->andReturnSelf();
        $payloadInterface->shouldReceive('withOutput')->once()->with([])->andReturnSelf();

        $userRepository = mockery::mock(UserRepository::class);
        $userRepository->shouldReceive('getOneByIdOrFail')->once()->with(1)->andReturn($user);

        $fractal = mockery::mock(Manager::class);
        $fractal->shouldReceive('createData')->once()->with($item)->andReturn($scope);

        $token = mockery::mock(Token::class);
        $token->shouldReceive('getMetadata')->once()->with('entity')->andReturn($user);

        $domain = mockery::mock(GetUsers::class . '[authorizeUser]', [$payloadInterface, $userRepository, $userTransformer, $fractal, $lockManager, $item]);
        $domain->shouldReceive('authorizeUser')->once()->with($user, 'view', 'users')->andReturnNull();

        $input = [
            'id' => 1,
            AuthHandler::TOKEN_ATTRIBUTE => $token,
        ];

        $payload = $domain->__invoke($input);
        $this->assertInstanceOf(PayloadInterface::class, $payload);
    }
}