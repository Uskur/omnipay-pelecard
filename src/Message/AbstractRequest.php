<?php
namespace Omnipay\Pelecard\Message;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Message\AbstractRequest as BaseAbstractRequest;
use RuntimeException;

/**
 * Abstract Request
 */
abstract class AbstractRequest extends BaseAbstractRequest
{

    protected $liveEndpoint = 'https://gateway20.pelecard.biz/PaymentGW/init';

    protected $request = [];

    /**
     * Build the request object
     *
     * @return array
     */
    public function getData()
    {
        $this->request = [];
        if ($this->getTestMode()) {
            $this->validate('testUser', 'testPassword', 'testTerminal');
            $this->request['user'] = $this->getParameter('testUser');
            $this->request['password'] = $this->getParameter('testPassword');
            $this->request['terminal'] = $this->getParameter('testTerminal');
        } else {
            $this->validate('user', 'password', 'terminal');
            $this->request['user'] = $this->getParameter('user');
            $this->request['password'] = $this->getParameter('password');
            $this->request['terminal'] = $this->getParameter('terminal');
        }

        return $this->request;
    }

    /**
     * Get user
     *
     * Use the User assigned by Pelecard.
     *
     * @return string
     */
    public function getUser()
    {
        if (empty($this->getParameter('user'))) {
            throw new InvalidRequestException('user must be set.');
        }
        return $this->getParameter('user');
    }

    /**
     * Set user
     *
     * Use the User assigned by Pelecard.
     *
     * @param string $value
     */
    public function setUser($value)
    {
        return $this->setParameter('user', $value);
    }

    /**
     * Get password
     *
     * Use the Password assigned by Pelecard.
     *
     * @return string
     */
    public function getPassword()
    {
        if (empty($this->getParameter('password'))) {
            throw new InvalidRequestException('password must be set.');
        }
        return $this->getParameter('password');
    }

    /**
     * Set password
     *
     * Use the Password assigned by Pelecard.
     *
     * @param string $value
     */
    public function setPassword($value)
    {
        return $this->setParameter('password', $value);
    }

    /**
     * Get terminal
     *
     * Use the terminal assigned by Pelecard.
     *
     * @return string
     */
    public function getTerminal()
    {
        if (empty($this->getParameter('terminal'))) {
            throw new InvalidRequestException('terminal must be set.');
        }
        return $this->getParameter('terminal');
    }

    /**
     * Set terminal
     *
     * Use the terminal assigned by Pelecard.
     *
     * @param string $value
     */
    public function setTerminal($value)
    {
        return $this->setParameter('terminal', $value);
    }

    public function getTestUser()
    {
        return $this->getParameter('testUser');
    }

    public function setTestUser($value)
    {
        return $this->setParameter('testUser', $value);
    }

    public function getTestPassword()
    {
        return $this->getParameter('testPassword');
    }

    public function setTestPassword($value)
    {
        return $this->setParameter('testPassword', $value);
    }

    public function getTestTerminal()
    {
        return $this->getParameter('testTerminal');
    }

    public function setTestTerminal($value)
    {
        return $this->setParameter('testTerminal', $value);
    }

    public function sendData($data)
    {
        return $this->createResponse($this->sendJsonRequest($this->getEndpoint(), $data));
    }

    public function sendJsonRequest($url, array $data)
    {
        $httpResponse = $this->httpClient->request('POST', $url, [
            'Content-Type' => 'application/json; charset=utf-8',
            'Accept' => 'application/json',
        ], json_encode($data));

        $decoded = json_decode((string)$httpResponse->getBody(), true);
        if ($decoded === null && json_last_error() !== JSON_ERROR_NONE) {
            throw new RuntimeException('Invalid JSON response from Pelecard.');
        }

        return $decoded;
    }

    /**
     * Get Pelecard's numeric code for the payment currency.
     *
     * @return int
     */
    public function getCurrencyCode()
    {
        $value = strtoupper((string)$this->getCurrency());

        if ($value === 'NIS' || $value === 'ILS') {
            return 1;
        }
        if ($value === 'USD') {
            return 2;
        }
        if ($value === 'EUR') {
            return 978;
        }
        throw new RuntimeException(sprintf('Unknown currency: %s', $value));
    }

    /**
     * Set the ISO payment currency, normalizing Pelecard's legacy NIS alias.
     *
     * @param string $value Currency code.
     * @return AbstractRequest
     */
    public function setCurrency($value)
    {
        if (strtoupper((string)$value) === 'NIS') {
            $value = 'ILS';
        }

        return parent::setCurrency($value);
    }

    protected function getEndpoint()
    {
        return $this->liveEndpoint;
    }

    protected function createResponse($data)
    {
        return $this->response = new Response($this, $data);
    }
}
