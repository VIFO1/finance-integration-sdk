<?php

namespace Modules\Services;

class VifoServiceFactory
{
    private $env;
    private $bank;
    private $loginAuthenticateUser;
    private $sendRequest;
    private $transferMoney;
    private $approveTransferMoney;
    private $otherRequest;
    private $webhook;
    public function __construct($env)
    {
        $this->env = $env;
        $this->loginAuthenticateUser = new VifoAuthenticate();
        $this->sendRequest = new VifoSendRequest($this->env);
        $this->bank = new VifoBank();
        $this->transferMoney = new VifoTransferMoney();
        $this->approveTransferMoney = new VifoApproveTransferMoney();
        $this->otherRequest = new VifoOtherRequest();
        $this->webhook = new Webhook();
    }

    public function checkAuthenticateUser(array $headers, string $username, string $password): array
    {

        $response = $this->loginAuthenticateUser->authenticateUser($headers, $username, $password);
        if (isset($response['errors']) || !isset($response['body']['access_token'])) {
            return [
                'status' => 'errors',
                'message' => 'Authentication failed',
                'status_code' => $response['status_code']
            ];
        }

        return $response;
    }

    public function checkGetBank(array $headers, array $body): array
    {
        $response = $this->bank->getBank($headers, $body);
        if (isset($response['errors'])) {
            return [
                'status' => 'errors',
                'message' => 'Authentication failed due to errors.Authentication failed. Please check your access token or credentials.',
                'status_code' => $response['status_code']
            ];
        }
        return $response;
    }

    public function checkGetBeneficiaryName(array $headers, array $body): array
    {
        if (empty($body['bank_code']) || empty($body['account_number'])) {
            return [
                'status' => 'errors',
                'message' => 'Required fields missing: bank_code or account_number.',
            ];
        }

        $response = $this->bank->getBeneficiaryName($headers, $body);

        return $response;
    }

    public function checkTransferMoney(array $headers, array $body): array
    {
        $response = $this->transferMoney->createTransferMoney($headers, $body);
        if (isset($response['errors'])) {
            return [
                'status' => 'errors',
                'message' => $response['body']['message'],
                'status_code' => $response['status_code'],
                'errors' => $response['errors']
            ];
        }
        return $response;
    }

    public function checkApproveTransferMoney(string $secretKey, string $timestamp, array $headers, array $body): array
    {
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

    public function checkOtherRequest(array $headers, string $key): array
    {
        $response = $this->otherRequest->checkOrderStatus($headers, $key);
        if (empty($response['body']['data'])) {
            return [
                'status' => 'error',
                'message' => 'Request failed due to errors: ' . $response['body']['message'],
                'status_code' => $response['status_code']
            ];
        }
        return $response;
    }

    public function checkWebhook(array $data, string $requestSignature, string $secretKey, string $timestamp): string
    {
        $result = $this->webhook->handleSignature($data, $requestSignature, $secretKey, $timestamp);


        if ($result) {
            return 'Webhook Authentication Successful.';
        } else {
            return 'Webhook Authentication Failed.';
        }
    }
}
