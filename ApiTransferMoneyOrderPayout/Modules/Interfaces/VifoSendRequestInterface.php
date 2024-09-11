<?php

namespace Modules\Interfaces;

interface VifoSendRequestInterface
{
    public function sendRequest(string $method, string $endpoint, array $headers, array $body): array;
}
