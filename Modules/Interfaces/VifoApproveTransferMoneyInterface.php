<?php

namespace Modules\Interfaces;

interface VifoApproveTransferMoneyInterface
{
    public function createSignature(array $body, string $secretKey, string $timestamp): string;
    public function approveTransfers(string $secretKey, string $timestamp, array $headers, array $body): array;
}
