<?php

namespace Omnipay\MyCard\Message;


use Omnipay\MyCard\Exception\DefaultException;
use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\NotificationInterface;
use Guzzle\Http\Client as HttpClient;

class NotificationResponse extends AbstractResponse implements NotificationInterface
{


    protected $token;


    public function setToken($value)
    {
        $this->token = $value;
    }


    public function isSuccessful()
    {
        return $this->getData()['code'] == 1 ? true : false;
    }


    public function getTransactionId()
    {
        return $this->getData()['transactionId'];
    }


    public function getTransactionReference()
    {
        return null;
    }


    public function getTransactionStatus()
    {
        return $this->getData()['code'] == 1 ? 'completed' : 'failed';
    }


    public function getMessage()
    {
        return $this->getData()['message'];
    }


    public function accept()
    {
        return $this->confirm();
    }


    public function success()
    {
        return $this->confirm();
    }


    public function confirm()
    {
        return $this->confirmTransaction();
    }


    // docs: 3.4 確認 MyCard 交易，並進行請款(Server to Server)
    // 注意: 二次扣款也会失败
    private function confirmTransaction()
    {
        $httpClient = new HttpClient('', array('curl.options' => array(CURLOPT_CONNECTTIMEOUT => 60)));
        $endpoint = $this->request->getEndpoint('b2b') . '/MyBillingPay/api/PaymentConfirm';
        $requestData = [
            'AuthCode' => $this->token
        ];
        $response = $httpClient->post($endpoint, null, $requestData)->send();
        $data = json_decode($response->getBody(), true);
        if ($data['ReturnCode'] != 1) {
            throw new DefaultException($data['ReturnMsg']);
        }
        return true;
    }


}