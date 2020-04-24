<?php declare(strict_types=1);

namespace App\PostStatistic;

use App\DTO\PostDTO;

class AverageNumberOfPostsPerUserMonth implements StatisticCalculator
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
        if (!array_key_exists($postDTO->getFromId(), $this->stat)) {
            $this->stat[$postDTO->getFromId()] = ['posts_count' => 0, 'uniq_months' => []];
        }
        $this->stat[$postDTO->getFromId()]['posts_count']++;
        $this->stat[$postDTO->getFromId()]['uniq_months'][$yearMonth] = 1;
    }

    /**
     * @return array
     */
    public function getStatistic(): array
    {
        ksort($this->stat, SORT_NATURAL);
        $aggregatedStat = [];
        foreach ($this->stat as $user => $stat) {
            $aggregatedStat[$user] = round($stat['posts_count'] / count($stat['uniq_months']), 2);
        }
        return $aggregatedStat;
    }
}
