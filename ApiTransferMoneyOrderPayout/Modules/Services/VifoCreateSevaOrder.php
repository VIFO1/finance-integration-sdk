<?php

namespace Modules\Services;

use Modules\Interfaces\VifoCreateSevaOrderInterface;

class VifoCreateSevaOrder implements VifoCreateSevaOrderInterface
{
    private $sendRequest;
    public function __construct()
    {
        $this->sendRequest = new VifoSendRequest();
    }
    private function validateSevaOrder(array $headers, array $body): array
    {

        $errors = [];

        if (!is_array($body)) {
            $errors[] = 'Body must be an array';
        }

        if (empty($headers) || !is_array($headers)) {
            $errors[] = 'headers must be a non-empty array';
        }

        $requiredFields = [
            'product_code',
            'phone',
            'fullname',
            'final_amount',
            'distributor_order_number',
            'benefiary_bank_code',
            'benefiary_account_no',
            'comment',
        ];

        foreach ($requiredFields as $fields) {
            if (empty($body[$fields])) {
                $errors[] = $fields . ' is required.';
            }
        }

        return $errors;
    }
    public function createSevaOrder(array $headers, array $body): array
    {
        $endpoint = '/v2/finance';

        $errors = $this->validateSevaOrder($headers, $body);
        if (!empty($errors)) {
            return ['errors' => $errors];
        }

        return $this->sendRequest->sendRequest('POST', $endpoint, $headers, $body);
    }
}
