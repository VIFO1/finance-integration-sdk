<?php
namespace Modules\CommonFunctions;

/**
 * Validate input for approving transfers.
 * @param string $secretKey The secret key for authentication.
 * @param string $timestamp The timestamp of the request.
 * @param array $body The body of the request.
 * @return array An array containing error messages if validation fails; otherwise, an empty array.
 */
function validateSignatureInputs(string $secretKey, string $timestamp, array $body): array
{
    $errors = [];
    if (empty($secretKey) || !is_string($secretKey)) {
        $errors[] = 'Invalid secret key';
    }

    if (empty($timestamp)) {
        $errors[] = 'Invalid timestamp';
    }

    if (empty($body) || !is_array($body)) {
        $errors[] = 'The body must be a non-empty array.';
    }

    return $errors;
}
