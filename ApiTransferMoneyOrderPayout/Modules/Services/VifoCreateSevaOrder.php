<?php

namespace Modules\Services;
use Modules\Interfaces\VifoCreateSevaOrderInterface;
use function Modules\CommonFunctions\validateCreateOrder;
class VifoCreateSevaOrder implements VifoCreateSevaOrderInterface
{
    private $sendRequest;
    private $a;
    public function __construct()
    {   
        $this->sendRequest = new VifoSendRequest();
    }
   
    public function createSevaOrder(array $headers, array $body): array
    {
        $endpoint = '/v2/finance';

        $errors = validateCreateOrder($headers, $body);
        if (!empty($errors)) {
            return ['errors' => $errors];
        }

        return $this->sendRequest->sendRequest('POST', $endpoint, $headers, $body);
    }
}
