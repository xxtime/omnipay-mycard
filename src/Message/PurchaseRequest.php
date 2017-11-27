<?php

namespace Omnipay\MyCard\Message;


use Exception;

class PurchaseRequest extends AbstractRequest
{

    protected $endpoint = [
        'live' => [
            'auth'     => 'https://b2b.mycard520.com.tw',
            'redirect' => 'https://www.mycard520.com.tw',
        ],
        'test' => [
            'auth'     => 'https://test.b2b.mycard520.com.tw',
            'redirect' => 'https://test.mycard520.com.tw',
        ]
    ];


    // 用戶在廠商端的伺服器編號,不可輸入中文
    private $serverId = '';


    // 用戶在廠商端的會員唯一識別編號
    private $customerId = '';


    // 付費方式: 此參數非必填，參數為空時將依 交易金額(Amount)和幣別 (Currency)判斷可用的付費方式 呈現給用戶選擇
    private $paymentType = '';


    // 品項代碼: 此參數非必填，參數為空時將依 交易金額(Amount)和幣別 (Currency)判斷可用的付費方式 呈現給用戶選擇
    private $itemCode = '';


    // 厂商是否自定义充值页面
    private $customPage = false;


    protected function getEndpoint($type = '')
    {
        $mode = $this->getTestMode() ? 'test' : 'live';
        return $this->endpoint[$mode][$type];
    }


    protected function requestAuth()
    {
        $endpoint = $this->getEndpoint('auth') . '/MyBillingPay/api/AuthGlobal';
        $requestData = [
            'FacServiceId' => $this->getAppId(),
            'FacTradeSeq'  => $this->getTransactionId(),
            'TradeType'    => $this->getParameter('tradeType'),
            'ServerId'     => $this->serverId,      // 服务器ID
            'CustomerId'   => $this->customerId,    // 用户ID
            'PaymentType'  => $this->paymentType,
            'ItemCode'     => $this->itemCode,
            'ProductName'  => $this->getDescription(),
            'Amount'       => $this->getAmount(),
            'Currency'     => $this->getCurrency(),
            'SandBoxMode'  => $this->getTestMode(),
            'Hash'         => $this->createSign('authCode'),
        ];
        $requestData = array_filter($requestData);
        $httpRequest = $this->httpClient->post($endpoint, null, $requestData);
        $httpResponse = $httpRequest->send();
        $body = json_decode($httpResponse->getBody(), true);
        if ($body['ReturnCode'] != 1) {
            throw new Exception($body['ReturnMsg']);
        }
        $this->setTransactionReference($body['TradeSeq']);
        $this->customPage = ($body['InGameSaveType'] == 1) ? true : false;
        return $body['AuthCode'];
    }


    protected function createSign($type = '')
    {
        switch ($type) {
            case 'authCode':
                $preSign =
                    $this->getAppId() . $this->getTransactionId() . $this->getParameter('tradeType') .
                    $this->serverId . $this->customerId . $this->paymentType . $this->itemCode . strtolower(urlencode($this->getDescription())) .
                    $this->getAmount() . $this->getCurrency() . $this->getTestMode() . $this->getAppKey();
                break;
        }
        return hash('sha256', $preSign);
    }


    public function getData()
    {
        $this->customerId = $this->getTransactionId();
        $data = [
            'authCode'   => $this->requestAuth(),
            'customPage' => $this->customPage
        ];
        return $data;
    }


    public function sendData($data)
    {
        $parameters = [
            'redirect' => $this->getEndpoint('redirect') . '/MyCardPay/?AuthCode=' . $data['authCode']
        ];
        return new PurchaseResponse($this, $parameters);
    }
}