<?php

namespace Modules\Interfaces;

interface VifoServiceFactoryInterface
{
    public function setUserToken(string $token): void;

    public function setAdminToken(string $token): void;

    public function getAuthorizationHeaders(string $type = 'user'): array;

    public function performUserAuthentication(string $username, string $password): array;

    public function fetchBankInformation(array $body): array;

    public function fetchBeneficiaryName(array $body): array;

    public function executeMoneyTransfer(array $body): array;

    public function approveMoneyTransfer(string $secretKey, string $timestamp, array $body): array;

    public function processOtherRequest(string $key): array;

    public function verifyWebhookSignature(array $data, string $requestSignature, string $secretKey, string $timestamp): bool;

    public function createRevaOrder(
        string $fullname,
        string $benefiaryAccountName,
        string $productCode = null,
        string $distributorOrderNumber,
        string $phone,
        string $email,
        string $address,
        float $finalAmount,
        string $comment,
        bool $bankDetail,
        string $qrType = null,
        string $endDate = null
    ): array;

    public function createSevaOrder(
        string $productCode = null,
        string $phone,
        string $fullname,
        float $finalAmount,
        string $distributorOrderNumber,
        string $beneficiaryBankCode,
        string $beneficiaryAccountNo,
        string $comment,
        string $sourceAccountNo

    ): array;
}
