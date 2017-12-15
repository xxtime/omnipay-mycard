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
        $this->data['raw'] = $fetchResponse->getData();     // 用查询数据覆盖掉通知过来的数据

        $this->status = static::STATUS_FAILED;
        if ($fetchResponse->getData()['PayResult'] == 3) {  // 交易成功為3; 交易失敗為0;
            $this->status = static::STATUS_COMPLETED;
        }

        // TODO :: 二次确认会失败
        $confirmResponse = $this->request->confirmTransaction();
        if ($confirmResponse->getData()['ReturnCode'] == 1) {
            $this->data['raw']['confirm'] = true;
        }
        else {
            $this->data['raw']['confirm'] = false;
        }
        return $this;
    }


}