<?php

namespace Modules\Services;

use Modules\Interfaces\VifoServiceFactoryInterface;

class VifoServiceFactory implements VifoServiceFactoryInterface
{
    private $env;
    private $bankService;
    private $loginAuthenticateUser;
    private $sendRequest;
    private $transferMoneyService;
    private $approveTransferMoneyService;
    private $otherRequestService;
    private $webhookHandler;
    private $headersService;
    private $headersLogin;
    private $userToken;
    private $adminToken;
    public function __construct($env)
    {
        $this->env = $env;
        $this->loginAuthenticateUser = new VifoAuthenticate();
        $this->sendRequest = new VifoSendRequest($this->env);
        $this->bankService  = new VifoBank();
        $this->transferMoneyService  = new VifoTransferMoney();
        $this->approveTransferMoneyService = new VifoApproveTransferMoney();
        $this->otherRequestService = new VifoOtherRequest();
        $this->webhookHandler = new Webhook();

        $this->headersService = [
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
    public function getHeadersService(string $type = 'user'): array
    {
        $token = $type == 'user' ? $this->userToken : $this->adminToken;

        return array_merge($this->headersService, [
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
        $headers = $this->getHeadersService('user');
        $response = $this->bankService->getBank($headers, $body);
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
        $headers = $this->getHeadersService('user');

        if (empty($body['bank_code']) || empty($body['account_number'])) {
            return [
                'status' => 'errors',
                'message' => 'Required fields missing: bank_code or account_number.',
            ];
        }

        $response = $this->bankService->getBeneficiaryName($headers, $body);

        return $response;
    }

    public function executeMoneyTransfer(array $body): array
    {
        $headers = $this->getHeadersService('user');

        $response = $this->transferMoneyService->createTransferMoney($headers, $body);
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
        $headers = $this->getHeadersService('admin');

        $requestSignature = $this->approveTransferMoneyService->createSignature($body, $secretKey, $timestamp);
        $headers['x-request-timestamp'] = $timestamp;
        $headers['x-request-signature'] = $requestSignature;

        $response = $this->approveTransferMoneyService->approveTransfers($secretKey, $timestamp, $headers, $body);

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
        $headers = $this->getHeadersService('user');

        $response = $this->otherRequestService->checkOrderStatus($headers, $key);
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
}
