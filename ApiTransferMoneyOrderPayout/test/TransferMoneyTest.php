<?php

namespace Test;

use PHPUnit\Framework\TestCase;
use Modules\Services\VifoServiceFactory;
use Modules\Services\VifoTransferMoney;

class TransferMoneyTest extends TestCase
{
    private $headers;
    private $transfer_Money;

    protected function setUp(): void
    {
        $this->headers = [
            'Authorization' => 'Bearer dummy_token',
        ];
        $this->transfer_Money = new VifoTransferMoney($this->headers);
    }

    public function testTransferMoney()
    {
        $response = $this->transfer_Money->createTransferMoney();


        $this->assertIsArray($response);
        $this->assertArrayHasKey('product_code', $response);
        $this->assertArrayHasKey('distributor_order_number', $response);
    }
}
