<?php declare(strict_types=1);

namespace App\Service;

interface HttpWrapper
{
    public function getUrl(): string;
    public function setParam(string $key, string $value): self;
    public function getParams(): array;
    public function get(): array;
    public function post(): array;
}
