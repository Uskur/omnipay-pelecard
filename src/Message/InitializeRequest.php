<?php
namespace Omnipay\Pelecard\Message;
/**
 * Initialize Request
 *
 * @method Response send()
 */
class InitializeRequest extends AbstractRequest
{
    public function getData()
    {
        $this->validate('amount', 'card');
        //$this->getCard()->validate();
        $data = parent::getData();
        $data['UserKey'] = $this->getTransactionId();
        $data['Total'] = $this->getAmountInteger();
        $data['Currency'] = $this->getCurrency();
        $data['GoodURL'] = $this->getReturnUrl();
        $data['ErrorURL'] = $this->getReturnUrl();
        $data['CancelURL'] = $this->getCancelUrl();
        $data['CardHolderName'] = $this->getCard()->getName();
        $data['CustomerAddressField'] = implode(' ',[$this->getCard()->getAddress1(),$this->getCard()->getAddress2()]);
        $data['CustomerCityField'] = $this->getCard()->getCity();
        $data['CustomerIndexField'] = $this->getCard()->getPostcode();
        $data['CustomerCountryField'] = $this->getCard()->getCountry();
        $data['EmailField'] = $this->getCard()->getEmail();
        
        return $data;
    }
    
    /**
     * Get GoodURL
     *
     * @return string
     */
    public function getReturnUrl()
    {
        if (empty($this->getParameter('returnUrl'))) {
            throw new InvalidRequestException('returnUrl must be set.');
        }
        return $this->getParameter('returnUrl');
    }
    
    /**
     * Set GoodURL
     *
     * @param string $value
     */
    public function setReturnUrl($value)
    {
        return $this->setParameter('returnUrl', $value);
    }
}
