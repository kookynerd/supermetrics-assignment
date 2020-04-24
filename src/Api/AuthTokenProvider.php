<?php declare(strict_types=1);

namespace App\Api;

use App\Api\Auth\Client;
use App\Api\Auth\Storage;
use App\Api\Auth\Token;

class AuthTokenProvider
{
    private Storage $tokenStorage;
    private Client $tokenClient;

    /**
     * AuthTokenProvider constructor.
     * @param Storage $tokenStorage
     * @param Client $tokenClient
     */
    public function __construct(
        Storage $tokenStorage,
        Client $tokenClient
    )
    {
        $this->tokenStorage = $tokenStorage;
        $this->tokenClient = $tokenClient;
    }

    /**
     * @return Token
     */
    public function getToken(): Token
    {
        $token = $this->tokenStorage->fetch();
        if ($token === null || !$token->isValid()) {
            $token = $this->renewToken($token);
        }

        return $token;
    }

    /**
     * @param Token|null $token
     * @return Token
     */
    public function renewToken(?Token $token = null): Token
    {
        $token = $this->tokenClient->renew($token);
        $this->tokenStorage->store($token);

        return $token;
    }
}
