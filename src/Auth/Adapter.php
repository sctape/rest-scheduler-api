<?php namespace Scheduler\Auth;

use Scheduler\Users\Repository\UserRepository;
use Spark\Auth\AdapterInterface;
use Spark\Auth\Credentials;
use Spark\Auth\Exception\InvalidException;
use Spark\Auth\Token;

/**
 * Class Adapter
 * @package Scheduler\Auth
 * @author Sam Tape <sctape@gmail.com>
 */
class Adapter implements AdapterInterface
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {

        $this->userRepository = $userRepository;
    }

    /**
     * Validates a specified authentication token.
     *
     * - If the specified token is invalid, an InvalidException instance is
     *   thrown.
     * - If a valid token is present, a corresponding Token instance is
     *   returned.
     * - If for some reason the token cannot be validated, an AuthException
     *   instance is thrown.
     *
     * @param string $token
     * @return \Spark\Auth\Token
     * @throws \Spark\Auth\Exception\InvalidException if an invalid auth token
     *         is specified
     * @throws \Spark\Auth\Exception\AuthException if another error occurs
     *         during authentication
     */
    public function validateToken($token)
    {
        if ($user = $this->userRepository->getOneByToken($token)) {
            return new Token($token, ['id' => $user->getId(), 'entity' => serialize($user)]);
        }

        throw new InvalidException;
    }

    /**
     * Validates a set of user credentials.
     *
     * - If the user credentials are valid, a new authentication token is
     *   created and a corresponding Token instance is returned.
     * - If the user credentials are invalid, an InvalidException instance is
     *   thrown.
     * - If for some reason the user credentials cannot be validated, an
     *   AuthException instance is thrown.
     *
     * @param \Spark\Auth\Credentials $credentials
     * @return \Spark\Auth\Token
     * @throws \Spark\Auth\Exception\InvalidException if an invalid auth token
     *         is specified
     * @throws \Spark\Auth\Exception\AuthException if another error occurs
     *         during authentication
     */
    public function validateCredentials(Credentials $credentials)
    {
        // TODO: Implement validateCredentials() method.
    }
}