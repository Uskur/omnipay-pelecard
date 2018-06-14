<?php
namespace Omnipay\Pelecard\Message;
/**
 * Status Request
 *
 * @method Response send()
 */
class StatusRequest extends AbstractRequest
{
    
    protected $liveEndpoint = 'https://gateway20.pelecard.biz/PaymentGW/GetTransaction';
    
    public function getData()
    {
        $data = parent::getData();
        $data['QAResultStatus']='000';
        $data['TransactionId'] = $this->getTransactionReference();
        return $data;
    }
    
}
