<?php

namespace Omnipay\MyCard\Message;


use Exception;

class PurchaseRequest extends AbstractRequest
{

    // 用戶在廠商端的伺服器編號,不可輸入中文
    protected $serverId = '';


    // 用戶在廠商端的會員唯一識別編號
    protected $customerId = '';


    // 付費方式: 此參數非必填，參數為空時將依 交易金額(Amount)和幣別 (Currency)判斷可用的付費方式 呈現給用戶選擇
    protected $paymentType = '';


    // 品項代碼: 此參數非必填，參數為空時將依 交易金額(Amount)和幣別 (Currency)判斷可用的付費方式 呈現給用戶選擇
    protected $itemCode = '';


    // 是否沙箱模式
    protected $sandboxMode = 'false';


    // 厂商是否自定义充值页面
    private $customPage = false;


    protected function requestToken()
    {
        $endpoint = $this->getEndpoint('b2b') . '/MyBillingPay/api/AuthGlobal';
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
            'SandBoxMode'  => $this->sandboxMode,
            'Hash'         => $this->createSign('token'),
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


    public function getData()
    {
        $this->customerId = $this->getTransactionId();
        $this->sandboxMode = $this->getTestMode() ? 'true' : 'false';
        $data = [
            'token'      => $this->requestToken(),
            'customPage' => $this->customPage
        ];
        return $data;
    }


    public function sendData($data)
    {
        $parameters = [
            'token' => $data['token']
        ];
        return new PurchaseResponse($this, $parameters);
    }

}