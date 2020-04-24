<?php declare(strict_types=1);

namespace AppUnitTests;

use App\DTO\PostDTO;
use DateTime;
use Exception;

class PostDTOFactory
{
    /**
     * @param int $number
     * @param array $postData
     * @return PostDTO[]
     * @throws Exception
     */
    public static function genPosts(int $number, array $postData = []): array
    {
        $posts = [];
        foreach (range(0, $number) as $i) {
            $posts[] = static::genPost($postData);
        }
        return $posts;
    }

    /**
     * @param array $postData
     * @return PostDTO
     * @throws Exception
     */
    public static function genPost($postData = []): PostDTO
    {
        if (!empty($postData['created_time'])) {
            $postData['created_time'] = (new DateTime())
                ->setTimestamp(strtotime($postData['created_time']))
                ->format(DATE_ATOM);
        }
        $data = [
            'id' => $postData['id'] ?? static::getRandomId(),
            'from_name' => $postData['from_name'] ?? static::getRandomString(random_int(5, 10)),
            'from_id' => $postData['from_id'] ?? static::getRandomUserId(),
            'message' => $postData['message'] ?? static::getRandomString(random_int(100, 700)),
            'type' => 'status',
            'created_time' => $postData['created_time'] ?? static::getRandomCreatedAt()
        ];

        return new PostDTO($data);
    }

    /**
     * @return string
     * @throws Exception
     */
    public static function getRandomId(): string
    {
        return 'post' . static::getRandomString(13) . '_' . self::getRandomString(8);
    }

    /**
     * @param int $length
     * @return string
     * @throws Exception
     */
    public static function getRandomString(int $length): string
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }

        return $randomString;
    }

    /**
     * @return string
     * @throws Exception
     */
    public static function getRandomUserId(): string
    {
        return 'user_' . random_int(0, 10000);
    }

    /**
     * @return string
     * @throws Exception
     */
    public static function getRandomCreatedAt(): string
    {
        $createdAt = (new DateTime())->setTimestamp(random_int(strtotime('2000-01-01'), time()));
        return $createdAt->format(DATE_ATOM);
    }
}
