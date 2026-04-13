<?php

namespace Modules\Services;

use Modules\Interfaces\VifoServiceFactoryInterface;

class VifoServiceFactory  implements VifoServiceFactoryInterface
{
    private $env;
    private $bank;
    private $loginAuthenticateUser;
    private $sendRequest;
    private $transferMoney;
    private $approveTransferMoney;
    private $otherRequest;
    private $webhookHandler;
    private $headers;
    private $headersLogin;
    private $userToken;
    private $adminToken;
    private $createOrder;
    private $orderReva;
    private $orderSeva;
    public function __construct($env)
    {
        $this->env = $env;
        $this->loginAuthenticateUser = new VifoAuthenticate();
        $this->sendRequest = new VifoSendRequest($this->env);
        $this->bank  = new VifoBank();
        $this->transferMoney  = new VifoTransferMoney();
        $this->approveTransferMoney = new VifoApproveTransferMoney();
        $this->otherRequest = new VifoOtherRequest();
        $this->webhookHandler = new Webhook();
        $this->orderReva = new VifoCreateRevaOrder();
        $this->orderSeva = new VifoCreateSevaOrder();
        $this->headers = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];

        $this->headersLogin = [
            'Accept' => 'application/json',
            'text/plain',
            '*/*',
            'Accept-Encoding' => 'gzip',
            'deflate',
            'Accept-Language' => '*',
        ];
        $this->userToken = null;
        $this->adminToken = null;
    }
    public function setUserToken(string $token): void
    {
        $this->userToken = $token;
    }

    public function setAdminToken(string $token): void
    {
        $this->adminToken = $token;
    }

    public function getAuthorizationHeaders(string $type = 'user'): array
    {
        $token = $type == 'user' ? $this->userToken : $this->adminToken;

        return array_merge($this->headers, [
            'Authorization' => 'Bearer ' . $token,
        ]);
    }

    public function performUserAuthentication(string $username, string $password): array
    {
        $response = $this->loginAuthenticateUser->authenticateUser($this->headersLogin, $username, $password);
        if (isset($response['errors']) || !isset($response['body']['access_token'])) {
            return [
                'status' => 'errors',
                'message' => 'Authentication failed',
                'status_code' => $response['status_code'] ? $response['status_code'] : ''
            ];
        }
        return $response;
    }

    public function fetchBankInformation(array $body): array
    {
        $headers = $this->getAuthorizationHeaders('user');
        $response = $this->bank->getBank($headers, $body);
        if (isset($response['errors'])) {
            return [
                'status' => 'errors',
                'message' => $response['errors'],
                'status_code' => $response['status_code'] ?? ''
            ];
        }
        return $response;
    }

    public function fetchBeneficiaryName(array $body): array
    {
        $headers = $this->getAuthorizationHeaders('user');

        if (empty($body['bank_code']) || empty($body['account_number'])) {
            return [
                'status' => 'errors',
                'message' => 'Required fields missing: bank_code or account_number.',
            ];
        }

        $response = $this->bank->getBeneficiaryName($headers, $body);

        return $response;
    }

    public function executeMoneyTransfer(array $body): array
    {
        $headers = $this->getAuthorizationHeaders('user');

        $response = $this->transferMoney->createTransferMoney($headers, $body);
        if (isset($response['errors'])) {
            return [
                'status' => 'errors',
                'message' => $response['body']['message'] ?? '',
                'status_code' => $response['status_code'] ?? '',
                'errors' => $response['errors']
            ];
        }
        return $response;
    }

    public function approveMoneyTransfer(string $secretKey, string $timestamp, array $body): array
    {
        $headers = $this->getAuthorizationHeaders('admin');

        $requestSignature = $this->approveTransferMoney->createSignature($body, $secretKey, $timestamp);
        if ($requestSignature === 'errors') {
            return [
                'status' => 'errors',
                'message' => 'Signature creation failed due to invalid inputs.',
                'status_code' => '400'
            ];
        }
        $headers['x-request-timestamp'] = $timestamp;
        $headers['x-request-signature'] = $requestSignature;

        $response = $this->approveTransferMoney->approveTransfers($secretKey, $timestamp, $headers, $body);

        if (isset($response['errors'])) {
            return [
                'status' => 'errors',
                'message' => $response['errors'],
                'status_code' => $response['status_code'] ?? ''
            ];
        }
        return $response;
    }

    public function processOtherRequest(string $key): array
    {
        $headers = $this->getAuthorizationHeaders('user');

        $response = $this->otherRequest->checkOrderStatus($headers, $key);
        if (isset($response['body'])) {
            return [
                'status_code' => $response['status_code'] ?? '',
                'body' => $response['body'],
            ];
        }
        return $response;
    }

    public function verifyWebhookSignature(array $data, string $requestSignature, string $secretKey, string $timestamp): bool
    {
        $result = $this->webhookHandler->handleSignature($data, $requestSignature, $secretKey, $timestamp);

        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function createRevaOrder(
        string $fullname,
        string $benefiaryAccountName,
        string $productCode = null,
        string $distributorOrderNumber,
        string $phone = null,
        string $email,
        string $address,
        float $finalAmount,
        string $comment,
        bool $bankDetail,
        string $qrType = null,
        string $endDate = null
    ): array {
        $headers = $this->getAuthorizationHeaders('user');
        $body = [
            'fullname' => $fullname,
            'benefiary_account_name' => $benefiaryAccountName,
            'product_code' => $productCode ? $productCode : 'REVAVF240101',
            'distributor_order_number' => $distributorOrderNumber,
            'phone' => $phone,
            'email' => $email,
            'address' => $address,
            'final_amount' => $finalAmount,
            'comment' => $comment,
            'bank_detail' => $bankDetail,
            'qr_type' => $qrType ? $qrType : null,
            'end_date' => $endDate ? $endDate : null,
        ];
        $response = $this->orderReva->createRevaOrder($headers, $body);
        if (isset($response['body'])) {
            return [
                'status' => 'errors',
                'body' => $response['body']
            ];
        }
        return $response;
    }

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

    ): array {
        $headers = $this->getAuthorizationHeaders('user');
        $body = [
            'product_code' => $productCode ? $productCode : 'SEVAVF240101',
            'phone' => $phone,
            'fullname' => $fullname,
            'final_amount' => $finalAmount,
            'distributor_order_number' => $distributorOrderNumber,
            'benefiary_bank_code' => $beneficiaryBankCode,
            'benefiary_account_no' => $beneficiaryAccountNo,
            'comment' => $comment,
            'source_account_no' => $sourceAccountNo
        ];
        $response = $this->orderSeva->createSevaOrder($headers, $body);
        if (isset($response['body'])) {
            return [
                'status' => 'errors',
                'body' => $response['body']
            ];
        }
        return $response;
    }
}
