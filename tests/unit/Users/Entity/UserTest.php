<?php namespace Scheduler\Codeception\Unit\Users\Entity;

use Codeception\TestCase\Test;
use Scheduler\Users\Entity\User;

/**
 * Class UserTest
 * @package Scheduler\Codeception\Unit\Users\Entity
 * @author Sam Tape <sctape@gmail.com>
 */
class UserTest extends Test
{
    public function testGetId()
    {
        $user = new User;
        $this->assertNull($user->getId());
    }

    public function testGetAndSetName()
    {
        $user = new User;
        $this->assertNull($user->getName());

        $user->setName('John Smith');
        $this->assertEquals('John Smith', $user->getName());
    }

    public function testGetAndSetRole()
    {
        $user = new User;
        $this->assertNull($user->getRole());

        $user->setRole(User::EMPLOYEE_ROLE);
        $this->assertEquals(User::EMPLOYEE_ROLE, $user->getRole());
    }

    public function testGetAndSetEmail()
    {
        $user = new User;
        $this->assertNull($user->getEmail());

        $user->setEmail('john@example.com');
        $this->assertEquals('john@example.com', $user->getEmail());
    }

    public function testGetAndSetPhone()
    {
        $user = new User;
        $this->assertNull($user->getPhone());

        $user->setPhone('123-456-7890');
        $this->assertEquals('123-456-7890', $user->getPhone());
    }

    public function testGetCallerType()
    {
        $user = new User;
        $this->assertEquals('users', $user->getCallerType());
    }

    public function testGetCallerId()
    {
        $user = new User;
        $this->assertNull($user->getCallerId());
    }

    public function testGetCallerRoles()
    {
        $user = new User;
        $this->assertEquals([null], $user->getCallerRoles());

        $user->setRole(User::EMPLOYEE_ROLE);
        $this->assertEquals([User::EMPLOYEE_ROLE], $user->getCallerRoles());
    }
}