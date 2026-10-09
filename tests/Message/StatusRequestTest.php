<?php
declare(strict_types=1);

namespace League\Pelecard\Test\Message;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Pelecard\Message\StatusRequest;
use Omnipay\Tests\TestCase;

class StatusRequestTest extends TestCase
{
    public function testStatusUsesTransactionReferenceWithoutForcedQaResult(): void
    {
        $request = new StatusRequest($this->getHttpClient(), $this->getHttpRequest());
        $request->initialize([
            'user' => 'merchant-user',
            'password' => 'merchant-password',
            'terminal' => 'merchant-terminal',
            'transactionReference' => 'transaction-123',
        ]);

        $data = $request->getData();

        $this->assertSame('transaction-123', $data['TransactionId']);
        $this->assertArrayNotHasKey('QAResultStatus', $data);
    }

    public function testProductionRequestsRequireCredentials(): void
    {
        $request = new StatusRequest($this->getHttpClient(), $this->getHttpRequest());
        $request->setTransactionReference('transaction-123');

        $this->expectException(InvalidRequestException::class);

        $request->getData();
    }
}
