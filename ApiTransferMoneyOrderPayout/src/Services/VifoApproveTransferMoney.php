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
    /**
     * Validate input for approving transfers.
     *
     * @param string $secretKey The secret key for authentication.
     * @param string $timestamp The timestamp of the request.
     * @param array $body The body of the request.
     * @return string Returns error message if validation fails, otherwise a success message.
     */
    private function validateApproveTransfersInput(string $secretKey, string $timestamp, array $body): string
    {
        if (empty($secretKey) || !is_string($secretKey)) {
            return 'Invalid secret key';
        }

        if (empty($timestamp)) {
            return 'Invalid timestamp';
        }

        if (empty($body)) {
            return 'Invalid body';
        }

        return 'Validation passed';
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

        if ($validation !== 'Validation passed') {
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
