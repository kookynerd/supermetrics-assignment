<?php declare(strict_types=1);

namespace App\Api\Auth;

use App\DTO\AuthTokenDTO;
use InvalidArgumentException;
use JsonException;

class FileStorage implements Storage
{
    private const TOKEN_FILE_NAME = 'token';

    private string $tokenStoragePath;

    /**
     * FileStorage constructor.
     * @param string $storageDir
     */
    public function __construct(string $storageDir)
    {
        $isWritable = is_dir($storageDir) || (mkdir($storageDir, 0774, true) && is_dir($storageDir));
        if (!$isWritable) {
            throw new InvalidArgumentException('Token file storage dir is not writable');
        }
        $this->tokenStoragePath = rtrim($storageDir, '/') . '/' . static::TOKEN_FILE_NAME;
    }

    /**
     * @param Token $authToken
     * @throws JsonException
     */
    public function store(Token $authToken): void
    {
        $jsonTokenString = json_encode([
            'expiration' => $authToken->getExpiration()->getTimestamp(),
            'token' => $authToken->getTokenValue()
        ], JSON_THROW_ON_ERROR, 512);
        file_put_contents($this->tokenStoragePath, $jsonTokenString, LOCK_EX);
    }

    /**
     * @return Token|null
     */
    public function fetch(): ?Token
    {
        if (!file_exists($this->tokenStoragePath)) {
            return null;
        }

        $tokenDTO = new AuthTokenDTO(json_decode(file_get_contents($this->tokenStoragePath), true));
        return new Token($tokenDTO->getExpiration(), $tokenDTO->getToken());
    }
}
