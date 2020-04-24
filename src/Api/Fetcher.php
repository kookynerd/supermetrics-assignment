<?php declare(strict_types=1);

namespace App\Api;

use Generator;

interface Fetcher
{
    /**
     * @return Generator
     */
    public function fetch(): Generator;
}
