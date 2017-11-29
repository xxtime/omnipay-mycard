<?php

namespace Omnipay\MyCard;


use Omnipay\Common\AbstractGateway;

class Gateway extends AbstractGateway
{


    public function getName()
    {
        return 'MyCard';
    }


    public function getDefaultParameters()
    {
        return [
            'TradeType' => '2',     //1:Android SDK (手遊適用) 2:WEB
        ];
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


    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\MyCard\Message\PurchaseRequest', $parameters);
    }


    public function acceptNotification(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\MyCard\Message\NotificationRequest', $parameters);
    }


}