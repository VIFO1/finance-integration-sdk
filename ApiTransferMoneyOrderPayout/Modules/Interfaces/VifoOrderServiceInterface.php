<?php

namespace Modules\Interfaces;

interface VifoOrderServiceInterface
{
    public function buildOrderBody(array $data): array;
    public function validateRequestInput(array $headers, array $body): array;
    public function createOrder(array $headers, array $data): array;
}
