<?php declare(strict_types=1);

namespace App\PostStatistic;

use App\DTO\PostDTO;

interface StatisticCalculator
{
    /**
     * @param PostDTO $postDTO
     */
    public function handlePost(PostDTO $postDTO): void;

    /**
     * @return array
     */
    public function getStatistic(): array;
}
