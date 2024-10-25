<?php

namespace Modules\Services;

use Modules\Interfaces\VifoCreateRevaOrderInterface;

class VifoCreateRevaOrder  implements VifoCreateRevaOrderInterface
{
    private $sendRequest;
    public function __construct()
    {
        $this->sendRequest = new VifoSendRequest();
    }
    private function validateRevaOrder(array $headers, array $body): array
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
            'distributor_order_number',
            'phone',
            'fullname',
            'final_amount',
            'benefiary_account_name',
            'comment',
        ];

        foreach ($requiredFields as $fields) {
            if (empty($body[$fields])) {
                $errors[] = $fields . ' is required.';
            }
        }

        return $errors;
    }
    public function createRevaOrder(array $headers, array $body): array
    {
        $endpoint = '/v2/finance';

        $errors = $this->validateRevaOrder($headers, $body);
        if (!empty($errors)) {
            return ['errors' => $errors];
        }

        return $this->sendRequest->sendRequest('POST', $endpoint, $headers, $body);
    }
}
