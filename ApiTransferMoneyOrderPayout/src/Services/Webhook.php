<?php

namespace ApiTransferMoneyOrderPayout\Services;

class Webhook
{
    /**
     * Validate the input parameters for a request.
     *
     * @param string $secretKey The secret key used for validation.
     * @param string $timestamp The timestamp of the request.
     * @param mixed $body The body of the request, can be of any type.
     * 
     * @return string|null Returns an error message if validation fails, or null if validation passes.
     */
    private function validate(string $secretKey, string $timestamp, $body): ?string
    {
        if (empty($secretKey)) {
            return 'Invalid secret key';
        }

        if (empty($timestamp)) {
            return 'Invalid timestamp';
        }

        if (empty($body)) {
            return 'Invalid body';
        }

        return null;
    }

    private function createSignature($body, $secretKey, $timestamp)
    {
        ksort($body);
        $payload = json_encode($body);
        $signatureString = $timestamp . $payload;

        return hash_hmac('sha256', $signatureString, $secretKey);
    }

    public function handle($data, $requestSignature, $secretKey, $timestamp)
    {
        if (is_object($data)) {
            $data = (array) $data;
        } else {
            $data = json_decode($data, true);
        }

        $validationResult = $this->validate($secretKey, $timestamp, $data);

        if ($validationResult) {
            return ['error' => $validationResult];
        }

        if ($validationResult !== true) {
            return $validationResult;
        }

        $generatedSignature = $this->createSignature($data, $secretKey, $timestamp);

        if ($requestSignature == $generatedSignature) {
            return true;
        } else {
            return false;
        }
    }
}
