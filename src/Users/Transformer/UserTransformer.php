<?php namespace Scheduler\Users\Transformer;

use League\Fractal\TransformerAbstract;
use Scheduler\Users\Contracts\User;

/**
 * Class UserTransformer
 * @package Scheduler\Users\Transformer
 * @author Sam Tape <sctape@gmail.com>
 */
class UserTransformer extends TransformerAbstract
{
    /**
     * @param User $user
     * @return array
     */
    public function transform(User $user = null)
    {
        if (is_null($user)) {
            return [];
        }

        return [
            'id'      => (int) $user->getId(),
            'name'   => $user->getName(),
            'role'  => $user->getRole(),
            'email' => $user->getEmail(),
            'phone' => $user->getPhone(),
            'links'   => [
                [
                    'rel' => 'self',
                    'uri' => '/users/'.$user->getId(),
                ]
            ],
        ];
    }
}