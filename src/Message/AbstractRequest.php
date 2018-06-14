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

    protected $testEndpoint = 'https://gateway20.pelecard.biz/PaymentGW/init';

    protected $request = [];

    /**
     * Build the request object
     *
     * @return array
     */
    public function getData()
    {
        $this->request = array();
        $this->request['user'] = $this->getParameter('user');
        $this->request['password'] = $this->getParameter('password');
        $this->request['terminal'] = $this->getParameter('terminal');
        $this->request['Language'] = $this->getParameter('Language');
        
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

    public function sendData($data)
    {
        $httpRequest = $this->httpClient->post($this->getEndpoint(), [
            'Content-Type' => 'application/json; charset=utf-8',
            'Accept' => 'application/json',
            'json' => json_encode($data)
        ], json_encode($data));
        $httpResponse = $httpRequest->send();
        return $this->createResponse($httpResponse->json());
    }

    /**
     * Sets the payment currency code.
     *
     * @param string $value
     * @return AbstractRequest Provides a fluent interface
     */
    public function setCurrency($value)
    {
        if ($value !== null) {
            $value = strtoupper($value);
        }
        if ($value == 'NIS')
            return $this->setParameter('currency', 1);
        if ($value == 'USD')
            return $this->setParameter('currency', 2);
        if ($value == 'EUR')
            return $this->setParameter('currency', 978);
        throw new RuntimeException('Unknown currency');
    }

    /**
     * Sets the language code.
     *
     * @param string $value
     */
    public function setLanguage($value)
    {
        if ($value !== null) {
            $value = strtoupper($value);
        }
        if(!in_array($value, ['HE','EN','RU'])) {
            throw new RuntimeException('Unknown language');
        }
        return $this->setParameter('Language', $value);
    }

    protected function getEndpoint()
    {
        return $this->getTestMode() ? $this->testEndpoint : $this->liveEndpoint;
    }

    protected function createResponse($data)
    {
        return $this->response = new Response($this, $data);
    }
}
