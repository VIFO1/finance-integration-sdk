<?php

namespace Modules\Interfaces;

interface  VifoOtherRequestInterface
{
    public function checkOrderStatus(array $headers, string $key): array;
}
