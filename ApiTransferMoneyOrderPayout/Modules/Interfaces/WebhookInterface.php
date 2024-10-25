<?php

namespace Modules\Interfaces;

interface WebhookInterface
{
    public function createSignature(array $body, string $secretKey, string $timestamp): string;
    public function handleSignature(array $data, string $requestSignature, string $secretKey, string $timestamp): bool;
}
