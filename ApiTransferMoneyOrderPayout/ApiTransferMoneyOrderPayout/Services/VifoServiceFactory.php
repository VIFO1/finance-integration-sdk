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
     * @param string $username The username to validate. Must be a non-empty string.
     * @param string $password The password to validate. Must be a non-empty string.
     * @param array $headers The headers for the HTTP request. Must be a non-empty array.
     * 
     * @return array An array containing error messages if validation fails. Each error message describes a specific validation issue.
     *               Returns an empty array if all input parameters are valid.
     */
    private function validateLoginInput(array $headers, string $username, string $password): array
    {
        $errors = [];
        if (empty($username) || !is_string($username)) {
            $errors[] = 'Invalid username';
        }

        if (empty($password) || !is_string($password)) {
            $errors[] = 'Invalid password';
        }

        if (empty($headers) || !is_array($headers)) {
            $errors[] = 'headers must be a non-empty array';
        }
        return $errors;
    }

    public function login($headers, $username, $password)
    {
        $errors = $this->validateLoginInput($headers, $username, $password);
        if (!empty($errors)) {
            return ['errors' => $errors];
        }

        $endpoint = '/v1/clients/web/admin/login';
        $body = $this->login->login($username, $password);
        $response = $this->sendRequest->sendRequest('POST', $endpoint, $headers, $body);

        return $response;
    }
}
