<?php

namespace Modules\Services;

use Modules\Interfaces\VifoAuthenticateInterface;

class VifoAuthenticate implements VifoAuthenticateInterface
{
    private $sendRequest;
    public function __construct()
    {
        $this->sendRequest = new VifoSendRequest();
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
        if (empty($username) || !is_string($username) || $username == '') {
            $errors[] = 'Invalid username';
        }

        if (empty($password) || !is_string($password) || $password == '') {
            $errors[] = 'Invalid password';
        }

        if (empty($headers) || !is_array($headers)) {
            $errors[] = 'headers must be a non-empty array';
        }
        return $errors;
    }
    /**
     * Build the body for the login request.
     *
     * @param string $username
     * @param string $password
     * @return array
     */
    private function buildLoginBody(string $username, string $password): array
    {
        return [
            'username' => $username,
            'password' => $password
        ];
    }

    public function authenticateUser(array $headers, string $username, string $password): array
    {
        $errors = $this->validateLoginInput($headers, $username, $password);
        if (!empty($errors)) {
            return ['errors' => $errors];
        }
        $endpoint = '/v1/clients/web/admin/login';
        $body = $this->buildLoginBody($username, $password);

        return $this->sendRequest->sendRequest('POST', $endpoint, $headers, $body);
    }
}
