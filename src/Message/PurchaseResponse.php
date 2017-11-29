<?php


namespace Omnipay\MyCard\Message;


use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;


class PurchaseResponse extends AbstractResponse implements RedirectResponseInterface
{


    public function isSuccessful()
    {
        return false;
    }


    public function isRedirect()
    {
        return true;
    }


    public function getRedirectMethod()
    {
        return 'GET';
    }


    public function getRedirectUrl()
    {
        return $this->request->getEndpoint('redirect') . '/MyCardPay/?AuthCode=' . $this->data['token'];
    }


    public function getRedirectData()
    {
        return null;
    }


    public function getTransactionId()
    {
        return $this->request->getTransactionId();
    }


    public function getTransactionReference()
    {
        return $this->request->getTransactionReference();
    }


}