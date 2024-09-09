<?php

namespace ApiTransferMoneyOrderPayout\Services;


class VifoServiceFactory
{
    private $env;
    public $login;
    private $sendRequest;

    public function __construct($env = 'dev')
    {
        $this->env = $env;
        $this->login = new VifoAuthenticate();
        $this->sendRequest = new VifoSendRequest($this->env);
    }

    public function login($headers,$username, $password)
    {
        if (is_object($headers)) {
            $headers = (array) $headers;
        }
        $endpoint = '/v1/clients/web/admin/login';
        $body = $this->login->login($username, $password);
        $response = $this->sendRequest->sendRequest('POST', $endpoint, $headers,$body);

        return $response;
    }

 
}
