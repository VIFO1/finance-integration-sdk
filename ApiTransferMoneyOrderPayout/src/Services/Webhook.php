<?php

namespace ApiTransferMoneyOrderPayout\Services;

class Webhook
{
    private function validate($secretKey, $timestamp, $body)
    {
        if (empty($secretKey) || !is_string($secretKey)) {
            return ['error' => 'Invalid secret key'];
        }

        if (empty($timestamp)) {
            return ['error' => 'Invalid timestamp'];
        }

        if (empty($body) || !is_array($body)) {
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

    public function handle($data, $requestSignature, $secretKey, $timestamp)
    {
        if (is_object($data)) {
            $data = (array) $data;
        } else {
            $data = json_decode($data, true);
        }

        $validationResult = $this->validate($secretKey, $timestamp, $data);

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
