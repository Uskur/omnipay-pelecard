<?php
declare(strict_types=1);

namespace League\Pelecard\Test;

use Omnipay\Pelecard\IframeGateway;
use Omnipay\Pelecard\Message\AuthorizeRequest;
use Omnipay\Tests\GatewayTestCase;

class GatewayTest extends GatewayTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->gateway = new IframeGateway($this->getHttpClient(), $this->getHttpRequest());
    }

    public function testPurchaseUsesIframeAuthorizeRequest(): void
    {
        $request = $this->gateway->purchase(['amount' => '10.00', 'currency' => 'ILS']);

        $this->assertInstanceOf(AuthorizeRequest::class, $request);
        $this->assertTrue($this->gateway->supportsPurchase());
        $this->assertSame('10.00', $request->getAmount());
        $this->assertSame('ILS', $request->getCurrency());
    }

    public function testTestCredentialsArePassedToRequests(): void
    {
        $this->gateway->setTestMode(true);
        $this->gateway->setTestUser('test-user');
        $this->gateway->setTestPassword('test-password');
        $this->gateway->setTestTerminal('test-terminal');

        $request = $this->gateway->status();

        $this->assertTrue($request->getTestMode());
        $this->assertSame('test-user', $request->getTestUser());
        $this->assertSame('test-password', $request->getTestPassword());
        $this->assertSame('test-terminal', $request->getTestTerminal());
    }

    public function testQaResultStatusIsPassedToPurchaseRequest(): void
    {
        $request = $this->gateway->purchase(['QAResultStatus' => '555']);

        $this->assertSame('555', $request->getQAResultStatus());
    }
}
