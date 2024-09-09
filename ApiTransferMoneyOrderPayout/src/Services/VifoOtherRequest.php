<?php

namespace ApiTransferMoneyOrderPayout\Services;

class VifoOtherRequest
{
    private $headers;
    private $sendRequest;

    public function __construct($headers)
    {
        $this->headers = $headers;
        $this->sendRequest = new VifoSendRequest();
    }
    /**
     * Validate the order key.
     *
     * @param string $key The order key to validate.
     * 
     * @return string The validation result. Returns 'Order key is required' if the key is empty,
     *                'Order key must be a string' if the key is not a string,
     *                or 'Validation passed' if the key is valid.
     */
    private function validateOrderKey(string $key): string
    {
        if (empty($key)) {
            return 'Order key is required';
        }

        if (!is_string($key)) {
            return 'Order key must be a string';
        }

        return 'Validation passed';
    }
    public function checkOrderStatus($key)
    {
        $validationResult = $this->validateOrderKey($key);
        if ($validationResult !== 'Validation passed') {
            return ['error' => $validationResult];
        }

        $endpoint = "/v2/finance/{$key}/status";

        $response = $this->sendRequest->sendRequest("GET", $endpoint, $this->headers, $body = "");
        return $response;
    }
}
