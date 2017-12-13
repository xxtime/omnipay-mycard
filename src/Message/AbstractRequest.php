<?php


namespace Omnipay\MyCard\Message;


class AbstractRequest extends \Omnipay\Common\Message\AbstractRequest
{

    // 用戶在廠商端的伺服器編號,不可輸入中文
    protected $serverId = '';


    // 用戶在廠商端的會員唯一識別編號
    protected $accountId = '';


    // 付費方式: 此參數非必填，參數為空時將依 交易金額(Amount)和幣別 (Currency)判斷可用的付費方式 呈現給用戶選擇
    protected $paymentType = '';


    // 品項代碼: 此參數非必填，參數為空時將依 交易金額(Amount)和幣別 (Currency)判斷可用的付費方式 呈現給用戶選擇
    protected $itemCode = '';


    protected $endpoint = [
        'live' => [
            'b2b'      => 'https://b2b.mycard520.com.tw',
            'redirect' => 'https://www.mycard520.com.tw',
        ],
        'test' => [
            'b2b'      => 'https://test.b2b.mycard520.com.tw',
            'redirect' => 'https://test.mycard520.com.tw',
        ]
    ];


    public function getEndpoint($type = '')
    {
        $mode = $this->getTestMode() ? 'test' : 'live';
        return $this->endpoint[$mode][$type];
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


    public function setAmount($value)
    {
        return $this->setParameter('amount', sprintf('%.2f', $value), $value);
    }


    public function getTradeType()
    {
        return $this->getParameter('tradeType');
    }


    public function setTradeType($value)
    {
        return $this->setParameter('tradeType', $value);
    }


    public function getServerId()
    {
        return $this->getParameter('serverId');
    }


    public function setServerId($value)
    {
        return $this->setParameter('serverId', $value);
    }


    public function getAccountId()
    {
        return $this->getParameter('accountId');
    }


    public function setAccountId($value)
    {
        return $this->setParameter('accountId', $value);
    }


    public function getPaymentType()
    {
        return $this->getParameter('paymentType');
    }


    public function setPaymentType($value)
    {
        return $this->setParameter('paymentType', $value);
    }


    public function getItemCode()
    {
        return $this->getParameter('itemCode');
    }


    public function setItemCode($value)
    {
        return $this->setParameter('itemCode', $value);
    }


    public function getData()
    {
    }


    public function sendData($data)
    {
    }

}