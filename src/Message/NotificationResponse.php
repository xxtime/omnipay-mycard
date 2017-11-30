<?php

namespace Omnipay\MyCard\Message;


use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\NotificationInterface;
use Omnipay\MyCard\Exception\DefaultException;

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
    }


}