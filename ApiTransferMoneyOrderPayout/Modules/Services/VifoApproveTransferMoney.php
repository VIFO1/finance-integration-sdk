<?php

namespace Modules\Services;

use Modules\Interfaces\VifoApproveTransferMoneyInterface;

class VifoApproveTransferMoney implements VifoApproveTransferMoneyInterface
{
    private $sendRequest;

    public function __construct()
    {
        $this->sendRequest = new VifoSendRequest();
    }
    /**
     * Validate input for approving transfers.
     *
     * @param string $secretKey The secret key for authentication.
     * @param string $timestamp The timestamp of the request.
     * @param array $body The body of the request.
     * @return array An array containing error messages if validation fails; otherwise, an empty array.
     */
    private function validateApproveTransfersInput(string $secretKey, string $timestamp, array $body): array
    {
        $errors = [];
        if (empty($secretKey) || !is_string($secretKey)) {
            $errors[] = 'Invalid secret key';
        }

        if (empty($timestamp)) {
            $errors[] = 'Invalid timestamp';
        }

        if (empty($body) || !is_array($body)) {
            $errors[] = 'The body must be a non-empty array.';
        }

        return $errors;
    }
    /**
     * Create a signature for the request.
     *
     * @param array $body The body of the request.
     * @param string $secretKey The secret key for authentication.
     * @param string $timestamp The timestamp of the request.
     *
     * @return string The generated signature.
     */
    private function createSignature(array $body, string $secretKey, string $timestamp): string
    {
        ksort($body);
        $payload = json_encode($body, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $signatureString = $timestamp . $payload;

        return hash_hmac('sha256', $signatureString, $secretKey);
    }

    /**
     * Approve transfers by sending a request.
     *
     * @param string $secretKey The secret key for authentication.
     * @param string $timestamp The timestamp of the request.
     * @param array $body The body of the request.
     * @param array $headers The headers of the request.
     * @return array The response from the request.
     */

    public function approveTransfers(string $secretKey, string $timestamp, array $headers, array $body): array
    {
        $errors  = $this->validateApproveTransfersInput($secretKey, $timestamp, $body);

        if (!empty($errors)) {
            return ['errors' => $errors];
        }

        $endpoint = '/v2/finance/confirm';

        $requestSignature = $this->createSignature($body, $secretKey, $timestamp);

        $headers = array_merge($headers, [
            'x-request-timestamp' => $timestamp,
            'x-request-signature' => $requestSignature
        ]);

        $response = $this->sendRequest->sendRequest('POST', $endpoint, $headers, $body);

        return $response;
    }
}
