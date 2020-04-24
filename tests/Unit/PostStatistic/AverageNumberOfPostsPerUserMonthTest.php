<?php declare(strict_types=1);

namespace AppUnitTests\PostStatistic;

use App\DTO\PostDTO;
use App\PostStatistic\AverageNumberOfPostsPerUserMonth;
use AppUnitTests\PostDTOFactory;
use PHPUnit\Framework\TestCase;

class AverageNumberOfPostsPerUserMonthTest extends TestCase
{
    /**
     * @dataProvider statProvider
     *
     * @param array $posts
     * @param array $expectedStat
     *
     * @covers       \App\PostStatistic\AverageNumberOfPostsPerUserMonth::handlePost
     * @covers       \App\PostStatistic\AverageNumberOfPostsPerUserMonth::getStatistic
     */
    public function testStatisticCalculation(array $posts, array $expectedStat): void
    {
        $statisticCalculator = new AverageNumberOfPostsPerUserMonth();
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
        $postsCreatedTimeUserId = [
            ['2019-01-01', 'user_1'],
            ['2019-01-02', 'user_1'],
            ['2019-01-02', 'user_2'],
            ['2019-02-01', 'user_1'],
            ['2019-02-02', 'user_1'],
            ['2019-02-28', 'user_1'],
            ['2019-02-03', 'user_2']
        ];
        $expectedStat = [
            'user_1' => 2.5,
            'user_2' => 1
        ];
        $posts = [];

        /**
         * @var string $createdTime
         * @var string $userId
         */
        foreach ($postsCreatedTimeUserId as [$createdTime, $userId]) {
            $posts[] = PostDTOFactory::genPost(['created_time' => $createdTime, 'from_id' => $userId]);
        }

        return [[$posts, $expectedStat]];
    }
}
