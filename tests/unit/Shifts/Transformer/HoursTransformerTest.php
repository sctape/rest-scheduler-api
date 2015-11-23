<?php namespace Scheduler\Codeception\Unit\Shifts\Transformer;

use Codeception\TestCase\Test;
use Mockery;
use Scheduler\Shifts\Transformer\HoursTransformer;

/**
 * Class HoursTransformerTest
 * @package Scheduler\Codeception\Unit\Shifts\Transformer
 * @author Sam Tape <sctape@gmail.com>
 */
class HoursTransformerTest extends Test
{
    /**
     * Test that the transformer returns a correctly formatted array
     */
    public function testTransform()
    {
        $hours = [
            'week_start' => '2015-01-01 00:00:00',
            'week_end' => '2015-01-07 23:59:59',
            'hours_count' => 25.2500,
        ];

        $transformer = new HoursTransformer;
        $transformedData = $transformer->transform($hours);

        $this->assertEquals([
            'week'      => "Thu, 01 Jan 2015 00:00:00 +0000 - Wed, 07 Jan 2015 23:59:59 +0000",
            'hours'      => 25.25,
        ], $transformedData);
    }
}