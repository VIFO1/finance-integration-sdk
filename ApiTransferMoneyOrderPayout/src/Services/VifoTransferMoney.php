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

    public function createTransferMoney($body)
    {
        $endpoint = '/v2/finance';

        if (is_object($body)) {
            $body = (array) $body;
        }

        $response = $this->sendRequest->sendRequest('POST', $endpoint, $this->headers, $body);

        return $response;
    }
}
