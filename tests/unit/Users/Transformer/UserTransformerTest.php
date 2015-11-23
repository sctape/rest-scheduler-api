<?php namespace Scheduler\Codeception\Unit\Users\Transformer;

use Codeception\TestCase\Test;
use Mockery;
use Scheduler\Users\Contracts\User;
use Scheduler\Users\Transformer\UserTransformer;

/**
 * Class UserTransformerTest
 * @package Scheduler\Codeception\Unit\Users\Transformer
 * @author Sam Tape <sctape@gmail.com>
 */
class UserTransformerTest extends Test
{
    /**
     * Test that the transformer returns the correctly formatted array
     */
    public function testTransform()
    {
        $user = mockery::mock(User::class);
        $user->shouldReceive('getId')->twice()->withNoArgs()->andReturn(1);
        $user->shouldReceive('getName')->once()->withNoArgs()->andReturn('John Smith');
        $user->shouldReceive('getRole')->once()->withNoArgs()->andReturn(User::EMPLOYEE_ROLE);
        $user->shouldReceive('getEmail')->once()->withNoArgs()->andReturn('john@example.com');
        $user->shouldReceive('getPhone')->once()->withNoArgs()->andReturn('123-456-7890');

        $transformer = new UserTransformer;
        $transformedData = $transformer->transform($user);

        $this->assertEquals([
            'id' => 1,
            'name' => 'John Smith',
            'role' => User::EMPLOYEE_ROLE,
            'email' => 'john@example.com',
            'phone' => '123-456-7890',
            'links' => [
                [
                    'rel' => 'self',
                    'uri' => '/users/1'
                ]
            ]
        ], $transformedData);
    }

    /**
     * Test that the transformer returns an empty array when not given a user
     */
    public function testTransformNullUser()
    {
        $transformer = new UserTransformer;
        $this->assertEquals([], $transformer->transform());
    }
}