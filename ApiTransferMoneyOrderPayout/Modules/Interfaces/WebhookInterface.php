<?php

namespace Modules\Interfaces;

interface WebhookInterface
{
    public function handleSignature(array $data, string $requestSignature, string $secretKey, string $timestamp): bool;
}
