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
    private $headerService;
    private $headersLogin;
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

        $this->headerService = [
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
    }
    public function updateAuthorizationHeader(string $accessToken): void
    {
        $this->headerService['Authorization'] = 'Bearer ' . $accessToken;
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

    public function fetchBankInformation(string $accessToken, array $body): array
    {
        $this->updateAuthorizationHeader($accessToken);
        $response = $this->bankService->getBank($this->headerService, $body);
        if (isset($response['errors'])) {
            return [
                'status' => 'errors',
                'message' => $response['errors'],
                'status_code' => $response['status_code'] ?? ''
            ];
        }
        return $response;
    }

    public function fetchBeneficiaryName(string $accessToken, array $body): array
    {
        $this->updateAuthorizationHeader($accessToken);
        if (empty($body['bank_code']) || empty($body['account_number'])) {
            return [
                'status' => 'errors',
                'message' => 'Required fields missing: bank_code or account_number.',
            ];
        }

        $response = $this->bankService->getBeneficiaryName($this->headerService, $body);

        return $response;
    }

    public function executeMoneyTransfer(string $accessToken, array $body): array
    {
        $this->updateAuthorizationHeader($accessToken);

        $response = $this->transferMoneyService->createTransferMoney($this->headerService, $body);
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

    public function approveMoneyTransfer(string $accessToken, string $secretKey, string $timestamp, array $body): array
    {
        $this->updateAuthorizationHeader($accessToken);

        $requestSignature = $this->approveTransferMoneyService->createSignature($body, $secretKey, $timestamp);
        $this->headerService['x-request-timestamp'] = $timestamp;
        $this->headerService['x-request-signature'] = $requestSignature;
        $this->headerService['Authorization'] = 'Bearer ' . $accessToken;


        $response = $this->approveTransferMoneyService->approveTransfers($secretKey, $timestamp, $this->headerService, $body);

        unset($this->headerService['x-request-timestamp']);
        unset($this->headerService['x-request-signature']);
        unset($this->headerService['Authorization']);

        if (isset($response['errors'])) {
            return [
                'status' => 'errors',
                'message' => $response['errors'],
                'status_code' => $response['status_code'] ?? ''
            ];
        }
        return $response;
    }

    public function processOtherRequest(string $accessToken, string $key): array
    {
        $this->updateAuthorizationHeader($accessToken);

        $response = $this->otherRequestService->checkOrderStatus($this->headerService, $key);
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
