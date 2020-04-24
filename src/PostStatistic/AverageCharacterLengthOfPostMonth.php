<?php declare(strict_types=1);

namespace App\PostStatistic;

use App\DTO\PostDTO;

class AverageCharacterLengthOfPostMonth implements StatisticCalculator
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
        if (!array_key_exists($yearMonth, $this->stat)) {
            $this->stat[$yearMonth] = ['messages_len' => 0, 'count' => 0];
        }
        $this->stat[$yearMonth]['messages_len'] += strlen($postDTO->getMessage());
        $this->stat[$yearMonth]['count']++;
    }

    /**
     * @return array
     */
    public function getStatistic(): array
    {
        krsort($this->stat);
        $aggregatedStat = [];
        foreach ($this->stat as $month => $stat) {
            $aggregatedStat[$month] = round($stat['messages_len'] / $stat['count'], 2);
        }
        return $aggregatedStat;
    }
}
