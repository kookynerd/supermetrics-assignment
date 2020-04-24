<?php declare(strict_types=1);

namespace App\Api\Auth;

interface Client
{
    /**
     * @param Token|null $authToken
     * @return Token
     */
    public function renew(?Token $authToken): Token;
}
