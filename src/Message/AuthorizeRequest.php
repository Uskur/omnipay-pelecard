<?php
namespace Omnipay\Pelecard\Message;
/**
 * Authorize Request
 *
 * @method Response send()
 */
class AuthorizeRequest extends AbstractRequest
{
    public function getData()
    {
        $this->validate('amount', 'card');
        //$this->getCard()->validate();
        $data = parent::getData();
        $data['UserKey'] = $this->getTransactionId();
        $data['Total'] = $this->getAmountInteger();
        $data['Currency'] = $this->getCurrencyCode();
        $data['GoodURL'] = $this->getReturnUrl();
        $data['ErrorURL'] = $this->getReturnUrl();
        $data['CancelURL'] = $this->getCancelUrl();
        $data['CardHolderName'] = $this->getCard()->getName();
        $data['CustomerAddressField'] = implode(' ',[$this->getCard()->getAddress1(),$this->getCard()->getAddress2()]);
        $data['CustomerCityField'] = $this->getCard()->getCity();
        $data['CustomerIndexField'] = $this->getCard()->getPostcode();
        $data['CustomerCountryField'] = $this->getCard()->getCountry();
        $data['EmailField'] = $this->getCard()->getEmail();
        if($this->getParameter('Language')) $data['Language'] = $this->getParameter('Language');
        if($this->getParameter('QAResultStatus')) $data['QAResultStatus'] = $this->getParameter('QAResultStatus');
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
}
