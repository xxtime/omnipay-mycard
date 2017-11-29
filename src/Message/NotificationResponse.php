<?php

namespace Omnipay\MyCard\Message;


use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\NotificationInterface;

class NotificationResponse extends AbstractResponse implements NotificationInterface
{


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


}