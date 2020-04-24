<?php declare(strict_types=1);

namespace App\SupermetricsIntegration;

use App\Api\AuthTokenProvider;
use App\Api\Fetcher;
use App\DTO\PostDTO;
use App\Service\HttpWrapper;
use Generator;
use JsonException;
use RuntimeException;

class PostFetcher implements Fetcher
{
    public const MIN_PAGE = 1;
    public const MAX_PAGE = 10;

    private HttpWrapper $httpWrapper;
    private AuthTokenProvider $tokenProvider;

    /**
     * PostFetcher constructor.
     * @param HttpWrapper $httpWrapper
     * @param AuthTokenProvider $tokenProvider
     */
    public function __construct(
        HttpWrapper $httpWrapper,
        AuthTokenProvider $tokenProvider
    )
    {
        $this->httpWrapper = $httpWrapper;
        $this->tokenProvider = $tokenProvider;
    }

    /**
     * @return Generator
     */
    public function fetch(): Generator
    {
        foreach (range(static::MIN_PAGE, static::MAX_PAGE) as $pageId) {
            /** @var PostDTO $postDto */
            foreach ($this->fetchPage($pageId) as $postDto) {
                yield $postDto;
            }
        }
    }

    /**
     * @param int $pageId
     * @return Generator
     */
    protected function fetchPage(int $pageId = self::MIN_PAGE): Generator
    {
        $pageId = min(max($pageId, static::MIN_PAGE), static::MAX_PAGE);
        try {
            $pageData = $this->httpWrapper
                ->setParam('sl_token', $this->tokenProvider->getToken()->getTokenValue())
                ->setParam('page', (string)$pageId)
                ->get();
        } catch (JsonException $exception) {
            throw new RuntimeException("Can't parse Supermetrics API response");
        }

        if (empty($pageData['data']['posts'])) {
            return [];
        }

        foreach ($pageData['data']['posts'] as $postData) {
            yield new PostDTO($postData);
        }
    }
}

