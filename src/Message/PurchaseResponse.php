<?php


namespace Omnipay\MyCard\Message;


use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;


class PurchaseResponse extends AbstractResponse
{


    protected $request;


    protected $data;


    public function __construct(RequestInterface $request, $data)
    {
        $this->request = $request;
        $this->data = $data;
    }


    public function isSuccessful()
    {
        return false;
    }


    public function isRedirect()
    {
        return true;
    }


    public function redirect()
    {
        header('Location:' . $this->data['redirect']);
        exit;
    }


    public function getTransactionId()
    {
        return $this->request->getTransactionId();
    }


    public function getTransactionReference()
    {
        return $this->request->getTransactionReference();
    }


    public function getMessage()
    {
    }


}