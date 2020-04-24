<?php declare(strict_types=1);

namespace App\PostStatistic;

use App\DTO\PostDTO;

class TotalPostsSplitByWeek implements StatisticCalculator
{
    /**
     * @var array
     */
    private array $stat;

    /**
     * @param PostDTO $postDTO
     */
    public function handlePost(PostDTO $postDTO): void
    {
        //o - ISO-8601 year number. This has the same value as Y, except that if
        //the ISO week number (W) belongs to the previous or next year, that
        //year is used instead.
        $yearWeek = $postDTO->getCreatedTime()->format('o-W');
        if (empty($this->stat[$yearWeek])) {
            $this->stat[$yearWeek] = 0;
        }
        $this->stat[$yearWeek]++;
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
