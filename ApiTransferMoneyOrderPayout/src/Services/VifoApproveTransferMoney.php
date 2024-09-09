<?php

namespace ApiTransferMoneyOrderPayout\Services;

class VifoApproveTransferMoney
{
    private $headers;
    private $sendRequest;

    public function __construct($headers)
    {
        $this->headers = $headers;
        $this->sendRequest = new VifoSendRequest();
    }

    private function validateApproveTransfersInput($secretKey, $timestamp, $body)
    {
        if (empty($secretKey) || !is_string($secretKey)) {
            return ['error' => 'Invalid secret key'];
        }

        if (empty($timestamp)) {
            return ['error' => 'Invalid timestamp'];
        }

        if (empty($body)) {
            return ['error' => 'Invalid body'];
        }

        return true;
    }

    private function createSignature($body, $secretKey, $timestamp)
    {
        if (is_object($body)) {
            $body = (array) $body;
        }
        
        ksort($body);
        $payload = json_encode($body);
        $signatureString = $timestamp . $payload;

        return hash_hmac('sha256', $signatureString, $secretKey);
    }

    public function approveTransfers($secretKey, $timestamp, $body)
    {
        $validation = $this->validateApproveTransfersInput($secretKey, $timestamp, $body);

        if ($validation !== true) {
            return $validation;
        }

        $endpoint = '/v2/finance/confirm';

        $requestSignature = $this->createSignature($body, $secretKey, $timestamp);

        $this->headers = array_merge($this->headers, [
            'x-request-timestamp' => $timestamp,
            'x-request-signature' => $requestSignature
        ]);

        $response = $this->sendRequest->sendRequest('POST', $endpoint, $this->headers, $body);

        return $response;
    }
}
