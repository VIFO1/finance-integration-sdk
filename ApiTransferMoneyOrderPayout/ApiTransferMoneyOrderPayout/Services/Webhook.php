<?php

namespace ApiTransferMoneyOrderPayout\Services;

class Webhook
{
    /**
     * Validate the input parameters for a request.
     *
     * @param string $secretKey The secret key used for validation. Must be a non-empty string.
     * @param string $timestamp The timestamp of the request. Must be a non-empty string.
     * @param array $body The prepared body as an array.
     * 
     * @return array An array containing error messages if validation fails. Each error message describes a specific validation issue.
     *               Returns an empty array if all input parameters are valid.
     */
    private function validate(string $secretKey, string $timestamp,array $body): array
    {
        $errors = [];
        if (empty($secretKey)) {
            $errors[] = 'Invalid secret key';
        }

        if (empty($timestamp)) {
            $errors[] = 'Invalid timestamp';
        }

        if (empty($body) || !is_array($body)) {
            $errors[] = 'body must be a non-empty array';
        }

        return $errors;
    }

    private function createSignature($body, $secretKey, $timestamp)
    {
        ksort($body);
        $payload = json_encode($body, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $signatureString = $timestamp . $payload;

        return hash_hmac('sha256', $signatureString, $secretKey);
    }

    public function handle($data, $requestSignature, $secretKey, $timestamp)
    {
        $errors = $this->validate($secretKey, $timestamp, $data);

        if (!empty($errors)) {
            return ['errors'=>$errors];
        }

        $generatedSignature = $this->createSignature($data, $secretKey, $timestamp);

        if ($requestSignature == $generatedSignature) {
            return true;
        } else {
            return false;
        }
    }
}
