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
        $this->request = array();
        if($this->getTestMode()){
            $this->request['user'] = 'testpelecard3';
            $this->request['password'] = 'Q3EJB8Ah';
            $this->request['terminal'] = '0962210';
        }
        else{
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

    public function sendData($data)
    {
        $response = $this->httpClient->request('POST', $this->getEndpoint(), [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json; charset=utf-8',
            'json' => json_encode($data)
        ], json_encode($data));
        
        $result = json_decode($response->getBody()->getContents(), true);

        return $this->createResponse($result);
    }

    /**
     * Gets the payment currency code.
     *
     * @param string $value
     * @return AbstractRequest Provides a fluent interface
     */
    public function getCurrencyCode()
    {
        $value = $this->getParameter('currency');
        if ($value !== null) {
            $value = strtoupper($value);
        }
        if ($value == 'NIS')
            return 1;
        if ($value == 'USD')
            return 2;
        if ($value == 'EUR')
            return 978;
        throw new RuntimeException('Unknown currency');
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
