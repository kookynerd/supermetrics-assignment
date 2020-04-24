<?php declare(strict_types=1);

namespace App\Api\Auth;

use DateTime;

class Token
{
    private DateTime $expiration;
    private string $token;

    /**
     * Token constructor.
     * @param DateTime $expiration
     * @param string $token
     */
    public function __construct(DateTime $expiration, string $token)
    {
        $this->expiration = $expiration;
        $this->token = $token;
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        return $this->expiration >= new DateTime();
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
    public function getTokenValue(): string
    {
        return $this->token;
    }
}
