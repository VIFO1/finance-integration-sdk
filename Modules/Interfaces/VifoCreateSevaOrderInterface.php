<?php

namespace Modules\Interfaces;

interface VifoCreateSevaOrderInterface
{
    public function createSevaOrder(array $headers, array $data): array;
}
