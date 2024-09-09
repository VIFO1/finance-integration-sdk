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
    /**
     * Validate the login input.
     *
     * @param string $username The username to validate.
     * @param string $password The password to validate.
     * 
     * @return string|null Returns an error message if validation fails, or null if validation passes.
     */
    private function validateLoginInput(string $username, string $password): ?string
    {
        if (empty($username) || !is_string($username)) {
            return 'Invalid username';
        }

        if (empty($password) || !is_string($password)) {
            return 'Invalid password';
        }

        return null;
    }

    public function login($headers, $username, $password)
    {
        if (is_object($headers)) {
            $headers = (array) $headers;
        }
        $validationError = $this->validateLoginInput($username, $password);
        if ($validationError) {
            return ['error' => $validationError];
        }

        $endpoint = '/v1/clients/web/admin/login';
        $body = $this->login->login($username, $password);
        $response = $this->sendRequest->sendRequest('POST', $endpoint, $headers, $body);

        return $response;
    }
}
