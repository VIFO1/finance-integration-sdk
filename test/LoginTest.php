<?php
namespace Test;

use PHPUnit\Framework\TestCase;
use Modules\Services\VifoServiceFactory;

class LoginTest extends TestCase
{
    private $vifoService;
    
    protected function setUp(): void
    {
        $this->vifoService = new VifoServiceFactory('dev');
    }
    public function testLogin()
    {
        $username = 'dummy_username';
        $password = 'dummy_password';

        $response = $this->vifoService->login($username, $password);

        $this->assertTrue($response !== null);
        $this->assertArrayHasKey('access_token', $response);
        $this->assertNotEmpty($response['access_token']);
    }
}
