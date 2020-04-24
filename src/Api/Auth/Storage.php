<?php declare(strict_types=1);

namespace App\Api\Auth;

interface Storage
{
    /**
     * @param Token $authToken
     */
    public function store(Token $authToken): void;

    /**
     * @return Token|null
     */
    public function fetch(): ?Token;
}
