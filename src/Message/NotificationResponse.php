<?php

namespace Omnipay\MyCard\Message;


use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\NotificationInterface;

class NotificationResponse extends AbstractResponse implements NotificationInterface
{


    protected $token;


    protected $status;


    public function setToken($value)
    {
        $this->token = $value;
    }


    public function isSuccessful()
    {
        return $this->status == static::STATUS_COMPLETED;
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
        return $this->status;
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
        // 查询
        $fetchResponse = $this->request->fetchTransaction($this->token);
        $this->data['raw'] = $fetchResponse->getData();
        if ($fetchResponse->getData()['PayResult'] != 3) { // 交易成功為3; 交易失敗為0;
            $this->status = static::STATUS_FAILED;
        }
        else {
            $this->status = static::STATUS_COMPLETED;
        }


        // 确认订单
        $confirmResponse = $this->request->confirmTransaction();
        if ($confirmResponse->getData()['ReturnCode'] == 1) {
            // TODO :: 二次确认会失败
            // $this->status = static::STATUS_COMPLETED;
        }
        return $this;
    }


}