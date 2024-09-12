<?php

namespace Modules\Interfaces;

interface VifoServiceFactoryInterface
{
    public function updateAuthorizationHeader(string $accessToken): void;

    public function performUserAuthentication(string $username, string $password): array;

    public function fetchBankInformation(string $accessToken, array $body): array;

    public function fetchBeneficiaryName(string $accessToken, array $body): array;

    public function executeMoneyTransfer(string $accessToken, array $body): array;

    public function approveMoneyTransfer(string $accessToken, string $secretKey, string $timestamp, array $body): array;

    public function processOtherRequest(string $accessToken, string $key): array;

    public function verifyWebhookSignature(array $data, string $requestSignature, string $secretKey, string $timestamp): bool;
}