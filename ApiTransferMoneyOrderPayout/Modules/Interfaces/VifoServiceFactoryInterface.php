<?php

namespace Modules\Interfaces;

interface VifoServiceFactoryInterface
{
    public function setUserToken(string $token): void;

    public function setAdminToken(string $token): void;

    public function getHeadersService(string $type = 'user'): array;

    public function performUserAuthentication(string $username, string $password): array;

    public function fetchBankInformation(array $body): array;

    public function fetchBeneficiaryName(array $body): array;

    public function executeMoneyTransfer(array $body): array;

    public function approveMoneyTransfer(string $secretKey, string $timestamp, array $body): array;

    public function processOtherRequest(string $key): array;

    public function verifyWebhookSignature(array $data, string $requestSignature, string $secretKey, string $timestamp): bool;
}
