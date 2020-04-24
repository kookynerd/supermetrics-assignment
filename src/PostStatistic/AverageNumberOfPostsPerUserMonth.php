<?php declare(strict_types=1);

namespace App\PostStatistic;

use App\DTO\PostDTO;
use DateTime;

class AverageNumberOfPostsPerUserMonth implements StatisticCalculator
{
    /**
     * @var array
     */
    private array $stat = [];

    /**
     * @var DateTime|null
     */
    private ?DateTime $latestHandledDate = null;

    /**
     * @param PostDTO $postDTO
     */
    public function handlePost(PostDTO $postDTO): void
    {
        $this->latestHandledDate = $this->latestHandledDate
            ? max($this->latestHandledDate, $postDTO->getCreatedTime())
            : $postDTO->getCreatedTime();

        if (!array_key_exists($postDTO->getFromId(), $this->stat)) {
            $this->stat[$postDTO->getFromId()] = [
                'posts_count' => 0,
                'earliestOccurrence' => $postDTO->getCreatedTime()
            ];
        }
        $this->stat[$postDTO->getFromId()]['posts_count']++;
        $this->stat[$postDTO->getFromId()]['earliestOccurrence'] = min(
            $this->stat[$postDTO->getFromId()]['earliestOccurrence'],
            $postDTO->getCreatedTime()
        );
    }

    /**
     * @return array
     */
    public function getStatistic(): array
    {
        ksort($this->stat, SORT_NATURAL);
        $aggregatedStat = [];
        foreach ($this->stat as $user => $stat) {
            $months = $this->diffInFullMonths($stat['earliestOccurrence'], $this->latestHandledDate);
            $aggregatedStat[$user] = round($stat['posts_count'] / $months, 2);
        }
        return $aggregatedStat;
    }

    /**
     * @param DateTime $from
     * @param DateTime $to
     * @return int
     */
    private function diffInFullMonths(DateTime $from, DateTime $to): int
    {
        $to = DateTime::createFromFormat('Y-m-d', $to->format('Y-m-t'));
        $from = DateTime::createFromFormat('Y-m-d', $from->format('Y-m-1'));

        $diff = $to->diff($from);

        return $diff->y * 12 + $diff->m + ($diff->d > 0 ? 1 : 0);
    }
}
