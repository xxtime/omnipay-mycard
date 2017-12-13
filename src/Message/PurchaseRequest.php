<?php

namespace Omnipay\MyCard\Message;


class PurchaseRequest extends AbstractRequest
{

    public function getData()
    {
        $token = new TokenRequest($this->httpClient, $this->httpRequest);
        $token->initialize($this->getParameters());
        $tokenData = $token->getToken();
        $this->setToken($tokenData['AuthCode']);
        $this->setTransactionReference($tokenData['TradeSeq']);

        return [
            // 厂商是否自定义充值页面
            'customPage' => ($tokenData['InGameSaveType'] == 1) ? true : false
        ];
    }


    public function sendData($parameters)
    {
        $parameters['token'] = $this->getToken();
        return new PurchaseResponse($this, $parameters);
    }

}