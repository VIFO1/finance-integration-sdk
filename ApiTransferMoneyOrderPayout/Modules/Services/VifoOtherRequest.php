<?php

namespace Modules\Services;

use Modules\Interfaces\VifoOtherRequestInterface;

class VifoOtherRequest implements VifoOtherRequestInterface
{
    private $sendRequest;

    public function __construct()
    {
        $this->sendRequest = new VifoSendRequest();
    }
    /**
     * Validate the order key.
     *
     * @param string $key The order key to validate.
     * 
     * @return array An array containing error messages if validation fails; otherwise, an empty array.        
     */
    private function validateOrderKey(array $headers, string $key): array
    {
        $errors = [];
     

        if (!is_string($key) || $key == '') {
            $errors[] = 'Order key must be a string and cannot be empty';
        }
        if (empty($headers) || !is_array($headers)) {
            $errors[] = 'headers must be a non-empty array';
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
    public function checkOrderStatus(array $headers, string $key): array
    {
        $errors = $this->validateOrderKey($headers, $key);
        if (!empty($errors)) {
            return ['errors' => $errors];
        }

        $endpoint = "/v2/finance/{$key}/status";

        return $this->sendRequest->sendRequest("GET", $endpoint, $headers, []);
    }
}
