<?php namespace Scheduler\Codeception\Unit\Shifts\Transformer;

use Codeception\TestCase\Test;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use Mockery;
use Scheduler\Shifts\Contracts\Shift;
use Scheduler\Shifts\Transformer\ShiftTransformer;
use Scheduler\Users\Contracts\User;

/**
 * Class ShiftTransformerTest
 * @package Scheduler\Codeception\Unit\Shifts\Transformer
 * @author Sam Tape <sctape@gmail.com>
 */
class ShiftTransformerTest extends Test
{
    /**
     * Test that the transformer returns a correctly formatted array
     */
    public function testTransform()
    {
        $startTime = new \DateTime();
        $endTime = new \DateTime("+2 hours");

        $shift = mockery::mock(Shift::class);
        $shift->shouldReceive('getId')->twice()->withNoArgs()->andReturn(1);
        $shift->shouldReceive('getBreak')->once()->withNoArgs()->andReturn(12.5);
        $shift->shouldReceive('getStartTime')->once()->withNoArgs()->andReturn($startTime);
        $shift->shouldReceive('getEndTime')->once()->withNoArgs()->andReturn($endTime);

        $transformer = new ShiftTransformer;
        $transformedData = $transformer->transform($shift);

        $this->assertEquals([
            'id' => 1,
            'break' => 12.5,
            'start_time' => $startTime->format('r'),
            'end_time' => $endTime->format('r'),
            'links' => [
                [
                    'rel' => 'self',
                    'uri' => '/shifts/1'
                ]
            ]
        ], $transformedData);
    }

    /**
     * Test that an employee can be included with the transformation
     */
    public function testIncludeEmployee()
    {
        $user = mockery::mock(User::class);

        $shift = mockery::mock(Shift::class);
        $shift->shouldReceive('getEmployee')->once()->withNoArgs()->andReturn($user);

        $transformer = new ShiftTransformer;
        $this->assertInstanceOf(Item::class, $transformer->includeEmployee($shift));
    }

    /**
     * Test that a manager can be included with the transformation
     */
    public function testIncludeManager()
    {
        $user = mockery::mock(User::class);

        $shift = mockery::mock(Shift::class);
        $shift->shouldReceive('getManager')->once()->withNoArgs()->andReturn($user);

        $transformer = new ShiftTransformer;
        $this->assertInstanceOf(Item::class, $transformer->includeManager($shift));
    }

    /**
     * Test that coworkers can be included with the transformation
     */
    public function testIncludeCoworkers()
    {
        $user = mockery::mock(User::class);

        $shift = mockery::mock(Shift::class);
        $shift->shouldReceive('getCoworkers')->once()->withNoArgs()->andReturn([$user]);

        $transformer = new ShiftTransformer;
        $this->assertInstanceOf(Collection::class, $transformer->includeCoworkers($shift));
    }
}