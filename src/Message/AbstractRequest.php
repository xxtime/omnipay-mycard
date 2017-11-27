<?php


namespace Omnipay\MyCard\Message;


class AbstractRequest extends \Omnipay\Common\Message\AbstractRequest
{

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

}