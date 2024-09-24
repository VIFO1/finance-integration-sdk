<?php

namespace Modules\Services;

use Modules\Interfaces\WebhookInterface;
use function Modules\CommonFunctions\generateSignature;
use function Modules\CommonFunctions\validateSignatureInputs;

class Webhook implements WebhookInterface
{

    public function createSignature(array $body, string $secretKey, string $timestamp): string
    {
        $errors  = validateSignatureInputs($secretKey, $timestamp, $body);

        if (!empty($errors)) {
            return 'errors'; $errors;
        }

        return generateSignature($body,$secretKey,$timestamp);
    }
    /**
     * Handle the validation of the request signature.
     *
     * @param array $data The data (body) of the request.
     * @param string $requestSignature The signature from the request headers.
     * @param string $secretKey The secret key for generating the signature.
     * @param string $timestamp The timestamp from the request headers.
     * 
     * @return bool True if the signature is valid, false otherwise.
     */
    public function handleSignature(array $data, string $requestSignature, string $secretKey, string $timestamp): bool
    {
        $errors = validateSignatureInputs($secretKey, $timestamp, $data);

        if (!empty($errors)) {
            return ['errors' => $errors];
        }

        $generatedSignature = $this->createSignature($data, $secretKey, $timestamp);

        if ($requestSignature == $generatedSignature) {
            return true;
        } else {
            return false;
        }
    }
}
