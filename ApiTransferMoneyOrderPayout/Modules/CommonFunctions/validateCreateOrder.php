<?php
namespace Modules\CommonFunctions;

/**
 * Prepare the body for the request.
 *
 * @param array $body must be an array
 * @return array The prepared body as an array.
 */
     function validateCreateOrder(array $headers, array $body): array
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
            'benefiary_account_no',
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
