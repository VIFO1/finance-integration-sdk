<?php

namespace ApiTransferMoneyOrderPayout\Services;

class VifoBank
{
    private $headers;
    private $sendRequest;
    public function __construct($headers)
    {
        $this->sendRequest = new VifoSendRequest();
        $this->headers = $headers;
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
        if(!is_array($body))
        {
            $errors[] = 'Body must be an array';
        }
        return $errors;
    }

    /**
     * Get bank information from the API.
     *
     * @param array $body The request body, must be an array
     * @return array The response from the API.
     */
    public function getBank($body)
    {
        $endpoint = '/v2/data/banks/napas';
        $errors = $this->prepareBody($body);
        if(!empty($errors))
        {
            return ['errors' => $errors];
        }

        $response = $this->sendRequest->sendRequest('GET', $endpoint, $this->headers, $body);
        return $response;
    }

    /**
     * Get the beneficiary name from the API.
     *
     * @param array $body The request body, must be an array
     * @return array The response from the API.
     */
    public function getBeneficiaryName($body)
    {
        $endpoint = '/v2/finance/napas/receiver';
        $preparedBody = $this->prepareBody($body);
        if(!empty($preparedBody))
        {
            return ['errors' => $preparedBody];
        }

        $response = $this->sendRequest->sendRequest('POST', $endpoint, $this->headers, $body);
        return $response;
    }
}
