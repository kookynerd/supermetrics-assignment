<?php declare(strict_types=1);

namespace App\PostStatistic;

use App\Api\Fetcher;
use App\DTO\PostDTO;
use InvalidArgumentException;

class StatisticPipelineFactory
{
    /** @var StatisticCalculator[] */
    private array $pipeline;

    /**
     * @var Fetcher
     */
    private Fetcher $fetcher;

    public function __construct(array $pipeline, Fetcher $fetcher)
    {
        foreach ($pipeline as $statisticCalculatorClassName) {
            $statisticCalculator = new $statisticCalculatorClassName();
            if (!$statisticCalculator instanceof StatisticCalculator) {
                throw new InvalidArgumentException('Invalid pipline statistic calculator');
            }
            $this->pipeline[] = $statisticCalculator;
        }

        $this->fetcher = $fetcher;
    }

    /**
     * @return array
     */
    public function calculateStatistic(): array
    {
        /** @var PostDTO $post */
        foreach ($this->fetcher->fetch() as $post) {
            foreach ($this->pipeline as $statisticCalculator) {
                $statisticCalculator->handlePost($post);
            }
        }
        $aggregatedStatistic = [];
        foreach ($this->pipeline as $statisticCalculator) {
            /** @var StatisticCalculator $statisticCalculator */
            $aggregatedStatistic[get_class($statisticCalculator)] = $statisticCalculator->getStatistic();
        }

        return $aggregatedStatistic;
    }

}
