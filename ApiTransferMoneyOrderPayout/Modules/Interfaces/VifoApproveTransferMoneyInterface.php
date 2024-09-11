<?php

namespace Modules\Interfaces;

interface VifoApproveTransferMoneyInterface
{
    public function approveTransfers(string $secretKey, string $timestamp, array $headers, array $body): array;
}
