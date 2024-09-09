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

    public function getBank($body)
    {
        $endpoint = '/v2/data/banks/napas';

        if (is_object($body)) {
            $body = (array) $body;
        }

        $response = $this->sendRequest->sendRequest('GET', $endpoint, $this->headers, $body);
        return $response;
    }



    public function getBeneficiaryName($body)
    {
        $endpoint = '/v2/finance/napas/receiver';
        
        if (is_object($body)) {
            $body = (array) $body;
        }

        $response = $this->sendRequest->sendRequest('POST', $endpoint, $this->headers, $body);
        return $response;
    }
}
