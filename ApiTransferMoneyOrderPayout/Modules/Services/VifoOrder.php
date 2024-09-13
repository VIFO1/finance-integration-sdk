<?php

namespace Modules\Services;

use Modules\Interfaces\VifoOrderServiceInterface;

class VifoOrder implements VifoOrderServiceInterface
{
    private $sendRequest;
    public function __construct()
    {
        $this->sendRequest = new VifoSendRequest();
    }
    /**
     * Prepare the body for the request.
     *
     * @param array $data must be an array
     * @return array The prepared body as an array.
     */

    public function buildOrderBody(array $data): array
    {
        return [
            'product_code' => $data['product_code'] ?? '',
            'phone' => $data['phone'] ?? '',
            'fullname' => $data['fullname'] ?? '',
            'final_amount' => $data['final_amount'] ?? 0,
            'distributor_order_number' => $data['distributor_order_number'] ?? '',
            'benefiary_bank_code' => $data['benefiary_bank_code'] ?? '',
            'benefiary_account_no' => $data['benefiary_account_no'] ?? '',
            'comment' => $data['comment'] ?? '',
            'source_account_no' => $data['source_account_no'] ?? '',
        ];
    }
    public function validateRequestInput(array $headers, array $body): array
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
    public function createOrder(array $headers, array $data): array
    {
        $endpoint = '/v2/finance';
        $body = $this->buildOrderBody($data);

        $errors = $this->validateRequestInput($headers, $body);
        if (!empty($errors)) {
            return ['errors' => $errors];
        }

        return $this->sendRequest->sendRequest('POST', $endpoint, $headers, $body);
    }
}
