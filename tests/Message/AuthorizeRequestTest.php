<?php
declare(strict_types=1);

namespace League\Pelecard\Test\Message;

use Omnipay\Common\CreditCard;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Pelecard\Message\AuthorizeRequest;
use Omnipay\Tests\TestCase;
use RuntimeException;

class AuthorizeRequestTest extends TestCase
{
    private AuthorizeRequest $request;

    protected function setUp(): void
    {
        parent::setUp();

        $this->request = new AuthorizeRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize([
            'user' => 'merchant-user',
            'password' => 'merchant-password',
            'terminal' => 'merchant-terminal',
            'transactionId' => 'order-123',
            'amount' => '12.34',
            'currency' => 'ILS',
            'returnUrl' => 'https://merchant.example/return',
            'cancelUrl' => 'https://merchant.example/cancel',
            'card' => new CreditCard(['firstName' => 'Test', 'lastName' => 'Customer']),
        ]);
    }

    public function testBuildsPelecardAmountAndCurrencyWithoutReplacingIsoCurrency(): void
    {
        $data = $this->request->getData();

        $this->assertSame(1234, $data['Total']);
        $this->assertSame(1, $data['Currency']);
        $this->assertSame('ILS', $this->request->getCurrency());
        $this->assertSame('order-123', $data['UserKey']);
    }

    public function testLegacyNisCurrencyIsNormalized(): void
    {
        $this->request->setCurrency('nis');

        $this->assertSame('ILS', $this->request->getCurrency());
        $this->assertSame(1, $this->request->getCurrencyCode());
    }

    public function testMapsUsdAndEurCurrencyCodes(): void
    {
        $this->request->setCurrency('USD');
        $this->assertSame(2, $this->request->getCurrencyCode());

        $this->request->setCurrency('EUR');
        $this->assertSame(978, $this->request->getCurrencyCode());
    }

    public function testRejectsUnknownCurrency(): void
    {
        $this->request->setCurrency('GBP');

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Unknown currency: GBP');

        $this->request->getCurrencyCode();
    }

    public function testUsesExplicitTestCredentials(): void
    {
        $this->request->setTestMode(true);
        $this->request->setTestUser('test-user');
        $this->request->setTestPassword('test-password');
        $this->request->setTestTerminal('test-terminal');

        $data = $this->request->getData();

        $this->assertSame('test-user', $data['user']);
        $this->assertSame('test-password', $data['password']);
        $this->assertSame('test-terminal', $data['terminal']);
    }

    public function testIncludesAnExplicitQaResultStatus(): void
    {
        $this->request->setQAResultStatus('555');

        $this->assertSame('555', $this->request->getData()['QAResultStatus']);
    }

    public function testTestModeRequiresExplicitCredentials(): void
    {
        $this->request->setTestMode(true);

        $this->expectException(InvalidRequestException::class);

        $this->request->getData();
    }
}
