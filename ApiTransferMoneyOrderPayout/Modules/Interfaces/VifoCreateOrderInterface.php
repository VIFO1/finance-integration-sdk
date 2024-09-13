<?php

namespace Modules\Interfaces;

interface VifoCreateOrderInterface
{
    public function validateRequestInput(array $headers, array $body): array;
    public function createOrder(array $headers, array $data): array;
}
