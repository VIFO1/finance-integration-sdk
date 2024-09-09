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
    
    public function checkOrderStatus($key)
    {
        if (!isset($key)) {
            return ['error' => 'Order key is required'];
        }
        
        if (!is_string($key)) {
            return ['error' => 'Order key must be a string'];
        }
        
        $endpoint = "/v2/finance/{$key}/status";

        $response = $this->sendRequest->sendRequest("GET", $endpoint, $this->headers, $body = "");
        return $response;
    }
}
