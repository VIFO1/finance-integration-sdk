<?php

namespace Modules\Interfaces;

interface VifoCreateRevaOrderInterface
{
    public function createRevaOrder(array $headers, array $data): array;
}
