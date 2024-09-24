<?php

namespace Modules\Services;
use Modules\Interfaces\VifoApproveTransferMoneyInterface;
use function Modules\CommonFunctions\generateSignature;
use function Modules\CommonFunctions\validateSignatureInputs;


class VifoApproveTransferMoney implements VifoApproveTransferMoneyInterface
{
    private $sendRequest;

    public function __construct()
    {
        $this->sendRequest = new VifoSendRequest();
    }

    public function createSignature(array $body, string $secretKey, string $timestamp): string
    {
        $errors  = validateSignatureInputs($secretKey, $timestamp, $body);

        if (!empty($errors)) {
            return 'errors'; $errors;
        }

        return generateSignature($body,$secretKey,$timestamp);
    }

    /**
     * Approve transfers by sending a request.
     *
     * @param string $secretKey The secret key for authentication.
     * @param string $timestamp The timestamp of the request.
     * @param array $body The body of the request.
     * @param array $headers The headers of the request.
     * @return array The response from the request.
     */

    public function approveTransfers(string $secretKey, string $timestamp, array $headers, array $body): array
    {
        $errors  = validateSignatureInputs($secretKey, $timestamp, $body);

        if (!empty($errors)) {
            return ['errors' => $errors];
        }

        $endpoint = '/v2/finance/confirm';

        return $this->sendRequest->sendRequest('POST', $endpoint, $headers, $body);
    }
}
