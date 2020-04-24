<?php declare(strict_types=1);

namespace AppUnitTests\PostStatistic;

use App\DTO\PostDTO;
use App\PostStatistic\AverageCharacterLengthOfPostMonth;
use AppUnitTests\PostDTOFactory;
use PHPUnit\Framework\TestCase;

class AverageCharacterLengthOfPostMonthTest extends TestCase
{
    /**
     * @dataProvider statProvider
     *
     * @param array $posts
     * @param array $expectedStat
     *
     * @covers       \App\PostStatistic\AverageCharacterLengthOfPostMonth::handlePost
     * @covers       \App\PostStatistic\AverageCharacterLengthOfPostMonth::getStatistic
     */
    public function testStatisticCalculation(array $posts, array $expectedStat): void
    {
        $statisticCalculator = new AverageCharacterLengthOfPostMonth();
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
        $postsCreatedTimeMessageLength = [
            ['2019-01-01', 100],
            ['2019-01-02', 150],
            ['2019-01-31', 200],
            ['2019-02-01', 200],
            ['2019-02-28', 100],
            ['2019-03-01', 3],
            ['2019-03-27', 50],
            ['2019-03-28', 50],
        ];
        $expectedStat = [
            '2019-01' => 150,
            '2019-02' => 150,
            '2019-03' => 34.33
        ];
        $posts = [];
        /**
         * @var string $createdTime
         * @var int $messageLen
         */
        foreach ($postsCreatedTimeMessageLength as [$createdTime, $messageLen]) {
            $posts[] = PostDTOFactory::genPost([
                'created_time' => $createdTime,
                'message' => PostDTOFactory::getRandomString($messageLen)
            ]);
        }

        return [[$posts, $expectedStat]];
    }
}
