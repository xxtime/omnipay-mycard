<?php


namespace Omnipay\MyCard\Message;


class AbstractRequest extends \Omnipay\Common\Message\AbstractRequest
{

    protected $endpoint = [
        'live' => [
            'b2b'      => 'https://b2b.mycard520.com.tw',
            'redirect' => 'https://www.mycard520.com.tw',
        ],
        'test' => [
            'b2b'      => 'https://test.b2b.mycard520.com.tw',
            'redirect' => 'https://test.mycard520.com.tw',
        ]
    ];


    public function getEndpoint($type = '')
    {
        $mode = $this->getTestMode() ? 'test' : 'live';
        return $this->endpoint[$mode][$type];
    }


    public function getAppId()
    {
        return $this->getParameter('appId');
    }


    public function setAppId($value)
    {
        return $this->setParameter('appId', $value);
    }


    public function getAppKey()
    {
        return $this->getParameter('appKey');
    }


    public function setAppKey($value)
    {
        return $this->setParameter('appKey', $value);
    }


    public function getTradeType()
    {
        return $this->getParameter('tradeType');
    }


    public function setTradeType($value)
    {
        return $this->setParameter('tradeType', $value);
    }


    public function getData()
    {
    }


    public function sendData($data)
    {
    }


    public function createSign($type = '')
    {
        switch ($type) {

            case 'token':
                $preSign =
                    $this->getAppId() . $this->getTransactionId() . $this->getParameter('tradeType') .
                    $this->serverId . $this->customerId . $this->paymentType . $this->itemCode . strtolower(urlencode($this->getDescription())) .
                    $this->getAmount() . $this->getCurrency() . $this->sandboxMode . $this->getAppKey();
                break;

            // preHashValue = ReturnCode + PayResult + FacTradeSeq + PaymentType + Amount + Currency + MyCardTradeNo + MyCardType + PromoCode + 廠商的 Key
            case 'returnHash':
                $preSign = $this->httpRequest->get('ReturnCode')
                    . $this->httpRequest->get('PayResult')
                    . $this->httpRequest->get('FacTradeSeq')
                    . $this->httpRequest->get('PaymentType')
                    . $this->httpRequest->get('Amount')
                    . $this->httpRequest->get('Currency')
                    . $this->httpRequest->get('MyCardTradeNo')
                    . $this->httpRequest->get('MyCardType')
                    . $this->httpRequest->get('PromoCode')
                    . $this->getAppKey();
                break;

        }

        return hash('sha256', $preSign);
    }

}