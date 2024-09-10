<?php

namespace ApiTransferMoneyOrderPayout\Services;

class VifoTransferMoney
{
    private $headers;
    private $sendRequest;
    public function __construct($headers)
    {
        $this->headers = $headers;
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
    public function createTransferMoney($body)
    {
        $endpoint = '/v2/finance';

        $errors = $this->prepareBody($body);
        if(!empty($errors))
        {
            return ['errors' => $errors];
        }

        $response = $this->sendRequest->sendRequest('POST', $endpoint, $this->headers, $body);

        return $response;
    }
}
