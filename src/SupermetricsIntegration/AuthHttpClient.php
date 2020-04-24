<?php declare(strict_types=1);

namespace App\SupermetricsIntegration;

use App\Api\Auth\Client;
use App\Api\Auth\Token;
use App\Service\HttpWrapper;
use DateTime;
use RuntimeException;
use JsonException;

class AuthHttpClient implements Client
{
    private HttpWrapper $httpWrapper;
    private ApiClient $apiClient;

    /**
     * AuthHttpClient constructor.
     * @param HttpWrapper $httpWrapper
     * @param ApiClient $apiClient
     */
    public function __construct(HttpWrapper $httpWrapper, ApiClient $apiClient)
    {
        $this->httpWrapper = $httpWrapper;
        $this->apiClient = $apiClient;
    }

    /**
     * @param Token|null $authToken
     * @return Token
     */
    public function renew(?Token $authToken): Token
    {
        try {
            $authData = $this->httpWrapper
                ->setParam('client_id', $this->apiClient->getClientId())
                ->setParam('email', $this->apiClient->getEmail())
                ->setParam('name', $this->apiClient->getName())
                ->post();
        } catch (JsonException $exception) {
            throw new RuntimeException("Can't parse Supermetrics API response");
        }

        if (!array_key_exists('data', $authData) || !array_key_exists('sl_token', $authData['data'])) {
            throw new RuntimeException("Can't authorise in Supermetrics API");
        }

        return new Token((new DateTime())->modify('+ 1 hour'), $authData['data']['sl_token']);
    }
}
