<?php

namespace Modules\Services;

class VifoTransferMoney
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
    public function createTransferMoney(array $headers, array $body): array
    {
        $endpoint = '/v2/finance';

        $errors = $this->prepareBody($body);
        if(!empty($errors))
        {
            return ['errors' => $errors];
        }

        $response = $this->sendRequest->sendRequest('POST', $endpoint, $headers, $body);

        return $response;
    }
}
