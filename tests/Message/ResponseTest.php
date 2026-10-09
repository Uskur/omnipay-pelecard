<?php
declare(strict_types=1);

namespace League\Pelecard\Test\Message;

use Exception;
use Omnipay\Pelecard\Message\Response;
use Omnipay\Pelecard\Message\StatusRequest;
use Omnipay\Tests\TestCase;

class ResponseTest extends TestCase
{
    private StatusRequest $request;

    protected function setUp(): void
    {
        parent::setUp();

        $this->request = new StatusRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize(['transactionReference' => 'request-reference']);
    }

    public function testGetsTransactionReferenceFromResultData(): void
    {
        $response = new Response($this->request, [
            'ResultData' => ['TransactionId' => 'result-reference'],
        ]);

        $this->assertSame('result-reference', $response->getTransactionReference());
    }

    public function testGetsTransactionReferenceFromPelecardCallback(): void
    {
        $response = new Response($this->request, [
            'PelecardTransactionId' => 'callback-reference',
            'PelecardStatusCode' => '555',
        ]);

        $this->assertSame('callback-reference', $response->getTransactionReference());
    }

    public function testGetsTransactionReferenceFromGenericCallback(): void
    {
        $response = new Response($this->request, ['TransactionId' => 'callback-reference']);

        $this->assertSame('callback-reference', $response->getTransactionReference());
    }

    public function testGetsTransactionReferenceFromRedirectUrl(): void
    {
        $response = new Response($this->request, [
            'URL' => 'https://gateway.example/pay?transactionId=url-reference',
        ]);

        $this->assertSame('url-reference', $response->getTransactionReference());
    }

    public function testFallsBackToRequestTransactionReference(): void
    {
        $response = new Response($this->request, ['StatusCode' => '555']);

        $this->assertSame('request-reference', $response->getTransactionReference());
    }

    public function testThrowsWhenNoTransactionReferenceExists(): void
    {
        $request = new StatusRequest($this->getHttpClient(), $this->getHttpRequest());
        $response = new Response($request, ['StatusCode' => '555']);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Unable to parse query to extract transaction reference.');

        $response->getTransactionReference();
    }

    public function testMessageIsNullWhenPelecardReturnsNoErrorObject(): void
    {
        $response = new Response($this->request, ['StatusCode' => '555']);

        $this->assertNull($response->getMessage());
    }
}
