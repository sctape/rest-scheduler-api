<?php namespace Scheduler\Codeception\Unit\Shifts\Entity;

use Codeception\TestCase\Test;
use Mockery;
use Scheduler\Shifts\Entity\Shift;
use Scheduler\Users\Contracts\User;

/**
 * Class ShiftTest
 * @package Scheduler\Codeception\Unit\Shifts\Entity
 * @author Sam Tape <sctape@gmail.com>
 */
class ShiftTest extends Test
{
    public function testGetId()
    {
        $shift = new Shift;
        $this->assertNull($shift->getId());
    }

    public function testGetAndSetManager()
    {
        $shift = new Shift;
        $this->assertNull($shift->getManager());

        $user = mockery::mock(User::class);
        $shift->setManager($user);
        $this->assertEquals($user, $shift->getManager());
    }

    public function testGetAndSetEmployee()
    {
        $shift = new Shift;
        $this->assertNull($shift->getEmployee());

        $user = mockery::mock(User::class);
        $shift->setEmployee($user);
        $this->assertEquals($user, $shift->getEmployee());
    }

    public function testGetAndSetBreak()
    {
        $shift = new Shift;
        $this->assertNull($shift->getBreak());

        $shift->setBreak(12.5);
        $this->assertEquals(12.5, $shift->getBreak());
    }

    public function testGetAndSetStartTime()
    {
        $shift = new Shift;
        $this->assertNull($shift->getStartTime());

        $startDateTime = new \DateTime('2015-01-01 00:00:00');

        $shift->setStartTime($startDateTime->format('Y-m-d H:i:s'));
        $this->assertEquals($startDateTime, $shift->getStartTime());
    }

    public function testGetAndSetEndTime()
    {
        $shift = new Shift;
        $this->assertNull($shift->getEndTime());

        $endDateTime = new \DateTime('2015-01-01 00:00:00');

        $shift->setEndTime($endDateTime->format('Y-m-d H:i:s'));
        $this->assertEquals($endDateTime, $shift->getEndTime());
    }

    public function testGetAndSetCoworkers()
    {
        $shift = new Shift;
        $this->assertNull($shift->getCoworkers());

        $user = mockery::mock(User::class);
        $shift->setCoworkers([$user]);
        $this->assertEquals([$user], $shift->getCoworkers());
    }
}