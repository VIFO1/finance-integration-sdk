<?php
namespace Test;

use Modules\Services\VifoBank;
use PHPUnit\Framework\TestCase;
use Modules\Services\VifoServiceFactory;

class BankTest extends TestCase
{
    private $headers;
    private $bank;

    protected function setUp(): void
    {
        $this->headers = [
            'Authorization' => 'Bearer dummy_token',
            'Content-Type' => 'application/json',
            'Authorization'=> 'BearerVifo12345'
        ];
        $this->bank = new VifoBank($this->headers);
    }

    public function testGetBank()
    {
        $response = $this->bank->getBank();

        $this->assertIsArray($response);
        $this->assertArrayHasKey('code',$response);

    }

    public function testgetBeneficiaryName()
    {
        $response = $this->bank->getBeneficiaryName();

        $this->assertIsArray($response);
        $this->assertArrayHasKey('beneficiary_name',$response);
      
    }
}
