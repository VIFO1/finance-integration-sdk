<?php
namespace Test;

use Modules\Services\VIfoOtherRequest;
use PHPUnit\Framework\TestCase;

class OtherRequestTest extends TestCase
{
    private $headers;
    private $other;

    protected function setUp(): void
    {
        $this->headers = [
            'Authorization' => 'BearerVifo12345'
        ];
        $this->other = new VIfoOtherRequest($this->headers);
    }

    public function testOtherRequest()
    {
        $data = [
            'data' => [
                'order_number' => 'XHS190HXnXXX'
            ]
        ];
        $response = $this->other->checkOrderStatus($data);

        $this->assertIsArray($data);
        $this->assertIsArray($response);
        
    }
}
