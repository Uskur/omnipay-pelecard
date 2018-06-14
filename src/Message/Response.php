<?php

namespace Omnipay\Pelecard\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;

/**
 * Response
 */
class Response extends AbstractResponse
{
    public function __construct(RequestInterface $request, $data)
    {
        $this->request = $request;
        $this->data = $data;
    }

    public function isSuccessful()
    {
        return $this->data['Error']['ErrCode'] == 0;
    }

    public function getTransactionReference()
    {
        if (isset($this->data['ConfirmationKey'])) {
            return $this->data['ConfirmationKey'];
        }
    }
    
    public function getRedirectUrl()
    {
        if (isset($this->data['URL'])) {
            return $this->data['URL'];
        }
    }
    
    public function isRedirect()
    {
        if (isset($this->data['URL']) && !empty($this->data['URL'])) {
            return true;
        }
        return false;
    }
    
    public function getMessage()
    {
        return $this->data['Error']['ErrMsg'];
    }

}
