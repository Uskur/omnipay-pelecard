<?php
namespace Omnipay\Pelecard;

use Omnipay\Common\AbstractGateway;

/**
 * Pelecard Gateway
 */
class IframeGateway extends AbstractGateway
{

    public function getName()
    {
        return 'Iframe';
    }

    public function getDefaultParameters()
    {
        return array(
            'user' => '',
            'password' => '',
            'terminal' => '',
            'testMode' => false
        );
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

    /**
     *
     * @return Message\AuthorizeRequest
     */
    public function authorize(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Pelecard\Message\InitializeRequest', $parameters);
    }
}
