<?php declare(strict_types=1);

namespace App\DTO;

use DateTime;
use InvalidArgumentException;

class AuthTokenDTO implements DTO
{
    private string $token;
    private DateTime $expiration;

    /**
     * AuthTokenDTO constructor.
     * @param array $tokenData
     */
    public function __construct(array $tokenData)
    {
        if (
            !array_key_exists('expiration', $tokenData)
            || !array_key_exists('token', $tokenData)
            || !is_numeric($tokenData['expiration'])
            || !is_string($tokenData['token'])
        ) {
            throw new InvalidArgumentException('Wrong token provided data');
        }
        $this->expiration = (new DateTime())->setTimestamp($tokenData['expiration']);
        $this->token = $tokenData['token'];
    }

    /**
     * @return DateTime
     */
    public function getExpiration(): DateTime
    {
        return $this->expiration;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }
}
