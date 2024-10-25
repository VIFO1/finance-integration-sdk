<?php
namespace Modules\CommonFunctions;
/**
     * Create a signature for the request.
     *
     * @param array $body The body of the request.
     * @param string $secretKey The secret key for authentication.
     * @param string $timestamp The timestamp of the request.
     *
     * @return string The generated signature.
     */
     function generateSignature(array $body, string $secretKey, string $timestamp): string
    {
        ksort($body);
        $payload = json_encode($body, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $signatureString = $timestamp . $payload;

        return hash_hmac('sha256', $signatureString, $secretKey);
    }

 
    