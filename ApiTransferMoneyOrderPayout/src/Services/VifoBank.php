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

    private function prepareBody($body)
    {
        if (is_object($body)) {
            $body = (array) $body;
        }
        return $body;
    }

    public function getBank($body)
    {
        $endpoint = '/v2/data/banks/napas';
        $preparedBody = $this->prepareBody($body);

        $response = $this->sendRequest->sendRequest('GET', $endpoint, $this->headers, $preparedBody);
        return $response;
    }

    public function getBeneficiaryName($body)
    {
        $endpoint = '/v2/finance/napas/receiver';
        $preparedBody = $this->prepareBody($body);

        $response = $this->sendRequest->sendRequest('POST', $endpoint, $this->headers, $preparedBody);
        return $response;
    }
}
