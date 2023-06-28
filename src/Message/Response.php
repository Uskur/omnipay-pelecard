<?php
namespace Omnipay\Pelecard\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;
use Omnipay\Common\Http\ClientInterface;
use Omnipay\Common\Http\Client as HttpClient;

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
        if (isset($this->data['StatusCode']) && $this->data['StatusCode'] === '000') {
            // do confirmation as suggested by the api documentation ValidateByUniqueKey.
            $url = 'https://gateway20.pelecard.biz/PaymentGW/ValidateByUniqueKey';
            //The parameter name in the confirmation JSON is UniqueKey because in case there is no UserKey (was not sent in the initial JSON) you can perform confirmation using TransactionId instead.
            $request = [
                "ConfirmationKey" => $this->data['ResultData']['ConfirmationKey'],
                "UniqueKey" => $this->request->getTransactionId()?$this->request->getTransactionId():$this->data['ResultData']['TransactionId'],
                "TotalX100" => $this->data['ResultData']['DebitTotal']
            ];


            $httpClient = new HttpClient();

            $httpResponse = $httpClient->request('POST', $url, [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json; charset=utf-8',
                'json' => json_encode($request)
            ], json_encode($request));
            return $httpResponse->getBody()->getContents() == 1;
        } else
            return false;
    }

    public function isCancelled()
    {
        if (isset($this->data['StatusCode']) && $this->data['StatusCode'] === '000') {
            return false;
        }
        return true;
    }

    public function isPending()
    {
        return false;
    }

    public function getTransactionReference()
    {
        if(isset($this->data['ResultData']['TransactionId'])) {
            return $this->data['ResultData']['TransactionId'];
        }
        if (isset($this->data['URL']) && ! empty($this->data['URL'])) {
            $url = parse_url($this->data['URL']);
            if (! empty($url['query'])) {
                parse_str($url['query'], $query);
                if (! empty($query['transactionId'])) {
                    return $query['transactionId'];
                }
            }
        }
        throw new \Exception('Unable to parse query to extract transaction reference.');
    }

    public function getRedirectUrl()
    {
        if (isset($this->data['URL'])) {
            return $this->data['URL'];
        }
    }

    public function isRedirect()
    {
        if (isset($this->data['URL']) && ! empty($this->data['URL'])) {
            return true;
        }
        return false;
    }

    public function getMessage()
    {
        return $this->data['Error']['ErrMsg'];
    }
}
