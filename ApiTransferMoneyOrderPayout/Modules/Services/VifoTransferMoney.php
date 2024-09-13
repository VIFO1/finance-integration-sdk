<?php

namespace Modules\Services;

use Modules\Interfaces\VifoTransferMoneyInterface;

class VifoTransferMoney implements VifoTransferMoneyInterface
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
    private function validateRequestInput(array $headers, array $body): array
    {
        $errors = [];
        if (!is_array($body)) {
            $errors[] = 'Body must be an array';
        }
        if (empty($headers) || !is_array($headers)) {
            $errors[] = 'headers must be a non-empty array';
        }
        return $errors;
    }
    public function createTransferMoney(array $headers, array $body): array
    {
        $endpoint = '/v2/finance';

        $errors = $this->validateRequestInput($headers, $body);
        if (!empty($errors)) {
            return ['errors' => $errors];
        }

        return $this->sendRequest->sendRequest('POST', $endpoint, $headers, $body);
    }
}
