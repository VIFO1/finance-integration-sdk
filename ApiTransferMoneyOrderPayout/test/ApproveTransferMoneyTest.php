<?php
namespace Test;

use Modules\Services\VifoApproveTransferMoney;
use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertIsArray;

class ApproveTransferMoneyTest extends TestCase{
    private $headers;
    private $approve;

    protected function setUp(): void
    {
        $this->headers = [
            'Authorization' => 'Bearer dummy_token',
        ];
        $this->approve = new VifoApproveTransferMoney($this->headers);
    }

    public function testApproveTransfer()
    {
        $data = [
            'data' => [
                ['id' => 123]
            ]
        ];
    
        $response = $this->approve->approveTransfers($data);

        $this->assertIsArray($response);
        $this->assertArrayHasKey('code',$response);
        $this->assertEquals('00', $response['code']);

        
    }
}