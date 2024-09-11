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
    private function prepareBody(array $body): array
    {
        $errors = [];
        if (!is_array($body)) {
            $errors[] = 'Body must be an array';
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
    public function getBank(array $headers,array $body): array
    {
        $endpoint = '/v2/data/banks/napas';
        $errors = $this->prepareBody($body);
        if (!empty($errors)) {
            return ['errors' => $errors];
        }

        $response = $this->sendRequest->sendRequest('GET', $endpoint, $headers, $body);
        return $response;
    }

    /**
     * Get the beneficiary name from the API.
     *
     * @param array $body The request body, must be an array
     * @param array $headers The request headers, must be an array
     * @return array The response from the API.
     */
    public function getBeneficiaryName(array $headers,array $body): array
    {
        $endpoint = '/v2/finance/napas/receiver';
        $preparedBody = $this->prepareBody($body);
        if (!empty($preparedBody)) {
            return ['errors' => $preparedBody];
        }

        $response = $this->sendRequest->sendRequest('POST', $endpoint, $headers, $body);
        return $response;
    }
}
