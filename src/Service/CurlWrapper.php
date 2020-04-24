<?php declare(strict_types=1);

namespace App\Service;

use RuntimeException;
use JsonException;

class CurlWrapper implements HttpWrapper
{
    private string $url;
    private array $params;

    /**
     * CurlWrapper constructor.
     * @param string $url
     * @param array $params
     */
    public function __construct(string $url, array $params = [])
    {
        $this->url = $url;
        $this->params = $params;
    }

    /**
     * @param string $key
     * @param string $value
     * @return $this
     */
    public function setParam(string $key, string $value): self
    {
        $this->params[$key] = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * @return array
     * @throws JsonException
     */
    public function get(): array
    {
        $params = http_build_query($this->getParams());
        $url = $this->getUrl() . '?' . $params;

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);

        $errNo = curl_errno($ch);
        $error = curl_error($ch);

        if ($errNo !== 0) {
            throw new RuntimeException($error, $errNo);
        }

        return json_decode($response, true, 512, JSON_THROW_ON_ERROR);
    }

    /**
     * @return array
     * @throws JsonException
     */
    public function post(): array
    {
        $ch = curl_init($this->getUrl());

        curl_setopt($ch, CURLOPT_POSTFIELDS, $this->getParams());
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);

        $errNo = curl_errno($ch);
        $error = curl_error($ch);

        if ($errNo !== 0) {
            throw new RuntimeException($error, $errNo);
        }

        return json_decode($response, true, 512, JSON_THROW_ON_ERROR);
    }
}
