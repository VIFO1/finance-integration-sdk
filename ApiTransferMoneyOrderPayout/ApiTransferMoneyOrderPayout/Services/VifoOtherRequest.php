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
     * @return array An array containing error messages if validation fails; otherwise, an empty array.        
     */
    private function validateOrderKey(string $key): array
    {
        $errors = [];
        if (empty($key)) {
            $errors[] = 'Order key is required';
        }

        if (!is_string($key)) {
            $errors[] = 'Order key must be a string';
        }

        return $errors;
    }

    /**
     * Check the status of an order.
     *
     * @param string $key The order key to check.
     *
     * @return array The response from the API.
     */
    public function checkOrderStatus($key)
    {
        $errors = $this->validateOrderKey($key);
        if (!empty($errors)) {
            return ['errors' => $errors];
        }

        $endpoint = "/v2/finance/{$key}/status";

        $response = $this->sendRequest->sendRequest("GET", $endpoint, $this->headers, $body = "");
        return $response;
    }
}
