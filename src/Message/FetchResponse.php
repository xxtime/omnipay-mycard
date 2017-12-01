<?php

/**
 * 交易查询
 */
namespace Omnipay\MyCard\Message;


use Omnipay\Common\Message\AbstractResponse;

class FetchResponse extends AbstractResponse
{

    public function isSuccessful()
    {
        return ($this->getData()['PayResult'] == 3) ? true : false;
    }


    public function isPaid()
    {
        return $this->isSuccessful();
    }


    public function getTransactionId()
    {
        return $this->getData()['FacTradeSeq'];
    }


    public function getAmount()
    {
        return $this->getData()['Amount'];
    }


    public function getCurrency()
    {
        return $this->getData()['Currency'];
    }


    public function getCard()
    {
        return $this->getData()['MyCardTradeNo'];
    }


    public function getMessage()
    {
        return $this->getData()['ReturnMsg'];
    }

}