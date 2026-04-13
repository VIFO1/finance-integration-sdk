<?php

namespace Modules\Services;

use Modules\Interfaces\VifoBankInterface;

class VifoBank implements VifoBankInterface
{
    private $sendRequest;
    public function __construct()
    {
        $this->sendRequest = new VifoSendRequest();
    }


    /**
     * Prepare the body for the request.
     *
     * @param array $body must be an array
     * @return array The prepared body as an array.
     */
    private function validateBody(array $headers, array $body): array
    {
        $errors = [];
        if (empty($body) || !is_array($body)) {
            $errors[] = 'Body must be a non-empty array.';
        }
        if (empty($headers) || !is_array($headers)) {
            $errors[] = 'headers must be a non-empty array';
        }
        return $errors;
    }

    /**
     * Get bank information from the API.
     *
     * @param array $body The request body, must be an array
     * @param array $headers The request headers, must be an array
     * @return array The response from the API.
     */
    public function getBank(array $headers): array
    {
        $errors = [];
        $endpoint = '/v2/data/banks/napas';
        if (empty($headers) || !is_array($headers)) {
           return $errors[] = 'headers must be a non-empty array';
        }

        return $this->sendRequest->sendRequest('GET', $endpoint, $headers,[]);
    }

    /**
     * Get the beneficiary name from the API.
     *
     * @param array $body The request body, must be an array
     * @param array $headers The request headers, must be an array
     * @return array The response from the API.
     */
    public function getBeneficiaryName(array $headers, array $body): array
    {
        $endpoint = '/v2/finance/napas/receiver';
        $errors = $this->validateBody($headers, $body);
        if (!empty($errors)) {
            return ['errors' => $errors];
        }

        return $this->sendRequest->sendRequest('POST', $endpoint, $headers, $body);
    }
}
