<?php declare(strict_types=1);

namespace AppUnitTests\PostStatistic;

use App\DTO\PostDTO;
use App\PostStatistic\LongestPostByCharacterLengthMonth;
use AppUnitTests\PostDTOFactory;
use PHPUnit\Framework\TestCase;

class LongestPostByCharacterLengthMonthTest extends TestCase
{
    /**
     * @dataProvider statProvider
     *
     * @param array $posts
     * @param array $expectedStat
     *
     * @covers       \App\PostStatistic\LongestPostByCharacterLengthMonth::handlePost
     * @covers       \App\PostStatistic\LongestPostByCharacterLengthMonth::getStatistic
     */
    public function testStatisticCalculation(array $posts, array $expectedStat): void
    {
        $statisticCalculator = new LongestPostByCharacterLengthMonth();
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
            ['2019-01-31', 210],
            ['2019-02-01', 200],
            ['2019-02-28', 100],
            ['2019-03-01', 51],
            ['2019-03-27', 50],
            ['2019-03-28', 3],
        ];
        $expectedStat = [
            '2019-01' => 210,
            '2019-02' => 200,
            '2019-03' => 51
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
