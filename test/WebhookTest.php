<?php

namespace Test;

use Modules\Services\Webhook;
use PHPUnit\Framework\TestCase;

class WebhookTest extends TestCase
{
    private $test;
    protected function setUp(): void
    {
        $this->test = new Webhook();
    }

    public function testWebhook()
    {
        $data = '';
        $requestSignature = '';
        $signature = '';


        $result = $this->test->handle($data, $requestSignature, $signature);


        $this->assertEquals('yes', $result);
    }
}
