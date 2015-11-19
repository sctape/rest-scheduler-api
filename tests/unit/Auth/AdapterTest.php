<?php namespace Scheduler\Codeception\Unit\Auth;

use Auryn\Injector;
use Codeception\TestCase\Test;
use Mockery;
use Scheduler\Auth\Adapter;
use Scheduler\Users\Contracts\User;
use Scheduler\Users\Repository\UserRepository;
use Spark\Auth\AdapterInterface;
use Spark\Auth\Credentials;
use Spark\Auth\Token;

/**
 * Class AdapterTest
 * @package Scheduler\Codeception\Unit\Auth
 * @author Sam Tape <sctape@gmail.com>
 */
class AdapterTest extends Test
{
    public function testConstructor()
    {
        $userRepository = mockery::mock(UserRepository::class);
        $injector = mockery::mock(Injector::class);

        $adapter = new Adapter($userRepository, $injector);
        $this->assertInstanceOf(AdapterInterface::class, $adapter);
    }

    public function testValidateTokenWithValidToken()
    {
        $token = '12345';
        $user = mockery::mock(User::class);
        $user->shouldReceive('getId')->once()->withNoArgs()->andReturn(1);

        $userRepository = mockery::mock(UserRepository::class);
        $userRepository->shouldReceive('getOneByToken')->once()->with($token)->andReturn($user);

        $injector = mockery::mock(Injector::class);

        $adapter = new Adapter($userRepository, $injector);

        $validatedToken = $adapter->validateToken($token);
        $this->assertInstanceOf(Token::class, $validatedToken);
        $this->assertEquals($user, $validatedToken->getMetadata('entity'));
        $this->assertEquals(1, $validatedToken->getMetadata('id'));
    }

    /**
     * @expectedException \Spark\Auth\Exception\AuthException
     */
    public function testValidateTokenWithInvalidToken()
    {
        $token = '54321';

        $userRepository = mockery::mock(UserRepository::class);
        $userRepository->shouldReceive('getOneByToken')->once()->with($token)->andReturnNull();

        $injector = mockery::mock(Injector::class);

        $adapter = new Adapter($userRepository, $injector);

        $validatedToken = $adapter->validateToken($token);
    }

    /**
     * @expectedException \Spark\Auth\Exception\AuthException
     */
    public function testValidateCredentials()
    {
        $credentials = mockery::mock(Credentials::class);

        $userRepository = mockery::mock(UserRepository::class);
        $injector = mockery::mock(Injector::class);

        $adapter = new Adapter($userRepository, $injector);

        $validatedToken = $adapter->validateCredentials($credentials);
    }
}