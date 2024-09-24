<?php

namespace Modules\Services;

use Modules\Interfaces\VifoCreateRevaOrderInterface;
use function Modules\CommonFunctions\validateCreateOrder;

class VifoCreateRevaOrder  implements VifoCreateRevaOrderInterface
{
    private $sendRequest;
    public function __construct()
    {
        $this->sendRequest = new VifoSendRequest();
    }
   
    public function createRevaOrder(array $headers, array $body): array
    {
        $endpoint = '/v2/finance';

        $errors = validateCreateOrder($headers, $body);
        if (!empty($errors)) {
            return ['errors' => $errors];
        }

        return $this->sendRequest->sendRequest('POST', $endpoint, $headers, $body);
    }
}
