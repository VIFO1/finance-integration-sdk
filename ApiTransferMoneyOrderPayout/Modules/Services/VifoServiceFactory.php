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
        $this->createOrder = new VifoCreateOrder();
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
                'status_code' => $response['status_code']
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
        if (empty($response['body']['data'])) {
            return [
                'status' => 'error',
                'message' => 'Request failed due to errors: ' . $response['body']['message'],
                'status_code' => $response['status_code'] ?? ''
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
        string $productCode,
        string $distributorOrderNumber,
        string $phone,
        string $fullname,
        float $finalAmount,
        string $beneficiaryAccountNo,
        string $beneficiaryBankCode,
        string $comment,
        string $sourceAccountNo
    ): array {
        $headers = $this->getAuthorizationHeaders('admin');
        $body = [
            'product_code' => $productCode,
            'phone' => $phone,
            'fullname' => $fullname,
            'final_amount' => $finalAmount,
            'distributor_order_number' => $distributorOrderNumber,
            'benefiary_bank_code' => $beneficiaryBankCode,
            'benefiary account no' => $beneficiaryAccountNo,
            'comment' => $comment,
            'source_account_no' => $sourceAccountNo,
        ];
        $response = $this->createOrder->createOrder($headers, $body);

        if (isset($response['errors'])) {
            return [
                'status' => 'errors',
                'message' => 'Order creation failed',
                $response['errors'],
                'status_code' => $response['status_code'] ?? ''
            ];
        }
        return $response;
    }

    public function createNevaOrder(
        string $productCode,
        string $distributorOrderNumber,
        string $phone,
        string $fullname,
        float $finalAmount,
        string $beneficiaryAccountNo,
        string $beneficiaryBankCode,
        string $comment,
        string $sourceAccountNo
    ): array {
        return $this->createRevaOrder(
            $productCode,
            $distributorOrderNumber,
            $phone,
            $fullname,
            $finalAmount,
            $beneficiaryAccountNo,
            $beneficiaryBankCode,
            $comment,
            $sourceAccountNo
        );
    }
}
