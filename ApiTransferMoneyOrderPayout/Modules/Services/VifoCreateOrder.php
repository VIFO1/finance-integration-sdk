<?php

namespace Modules\Services;

use Modules\Interfaces\VifoCreateOrderInterface;

class VifoCreateOrder implements VifoCreateOrderInterface
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

    public function validateRequestInput(array $headers, array $body): array
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
            'benefiary account no',
            'benefiary_bank_code',
            'comment',
            
        ];

        foreach ($requiredFields as $fields) {
            if (empty($body[$fields])) {
                $errors[] = $fields . 'is required.';
            }
        }

        return $errors;
    }
    public function createOrder(array $headers, array $body): array
    {
        $endpoint = '/v2/finance';

        $errors = $this->validateRequestInput($headers, $body);
        if (!empty($errors)) {
            return ['errors' => $errors];
        }

        return $this->sendRequest->sendRequest('POST', $endpoint, $headers, $body);
    }
}
