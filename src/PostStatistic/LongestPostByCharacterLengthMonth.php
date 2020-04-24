<?php declare(strict_types=1);

namespace App\PostStatistic;

use App\DTO\PostDTO;

class LongestPostByCharacterLengthMonth implements StatisticCalculator
{
    /**
     * @var array
     */
    private array $stat = [];

    /**
     * @param PostDTO $postDTO
     */
    public function handlePost(PostDTO $postDTO): void
    {
        $yearMonth = $postDTO->getCreatedTime()->format('Y-m');
        if (empty($this->stat[$yearMonth])) {
            $this->stat[$yearMonth] = 0;
        }
        $this->stat[$yearMonth] = max($this->stat[$yearMonth], strlen($postDTO->getMessage()));
    }

    /**
     * @return array
     */
    public function getStatistic(): array
    {
        krsort($this->stat);
        return $this->stat;
    }
}
