<?php declare(strict_types=1);

namespace AppUnitTests\PostStatistic;

use App\DTO\PostDTO;
use App\PostStatistic\TotalPostsSplitByWeek;
use AppUnitTests\PostDTOFactory;
use PHPUnit\Framework\TestCase;

class TotalPostsSplitByWeekTest extends TestCase
{
    /**
     * @dataProvider statProvider
     *
     * @param array $posts
     * @param array $expectedStat
     *
     * @covers       \App\PostStatistic\TotalPostsSplitByWeek::handlePost
     * @covers       \App\PostStatistic\TotalPostsSplitByWeek::getStatistic
     */
    public function testStatisticCalculation(array $posts, array $expectedStat): void
    {
        $statisticCalculator = new TotalPostsSplitByWeek();
        /** @var PostDTO $post */
        foreach ($posts as $post) {
            $statisticCalculator->handlePost($post);
        }
        $this->assertEquals($expectedStat, $statisticCalculator->getStatistic());
    }

    /**
     * @return array|\array[][]
     * @throws \Exception
     */
    public function statProvider(): array
    {
        $postsCreatedTime = [
            '2018-12-30',
            '2018-12-31',
            '2019-01-01',
            '2019-01-06',
            '2019-01-07',
            '2019-12-31',
            '2020-01-01'
        ];
        $expectedStat = [
            '2020-01' => 2,
            '2019-02' => 1,
            '2019-01' => 3,
            '2018-52' => 1,
        ];
        $posts = [];

        foreach ($postsCreatedTime as $createdTime) {
            $posts[] = PostDTOFactory::genPost(['created_time' => $createdTime]);
        }

        return [[$posts, $expectedStat]];
    }
}
