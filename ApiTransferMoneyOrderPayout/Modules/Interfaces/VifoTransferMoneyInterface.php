<?php

namespace Modules\Interfaces;

interface VifoTransferMoneyInterface
{
    public function createTransferMoney(array $headers, array $body): array;
}
