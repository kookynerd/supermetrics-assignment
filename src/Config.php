<?php declare(strict_types=1);

namespace App;

final class Config
{
    public const TOKEN_FILE_STORAGE = __DIR__ . '/../data';

    public const SUPERMETRIC_BASE_URL = 'https://api.supermetrics.com';
    public const SUPERMETRICS_AUTH_ENTRY_POINT = self::SUPERMETRIC_BASE_URL . '/assignment/register';
    public const SUPERMETRICS_POSTS_ENTRY_POINT = self::SUPERMETRIC_BASE_URL . '/assignment/posts';

    public const SUPERMETRICS_CLIENT_ID = 'ju16a6m81mhid5ue1z3v2g0uh';
    public const SUPERMETRICS_CLIENT_EMAIL = 'justone1024@gmail.com';
    public const SUPERMETRICS_CLIENT_NAME = 'Andrei Baranov';
}
