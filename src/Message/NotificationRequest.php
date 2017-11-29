<?php

namespace Omnipay\MyCard\Message;


use Omnipay\MyCard\Exception\DefaultException;

class NotificationRequest extends AbstractRequest
{

    private $ip = [
        '220.130.127.125',  // MyCard正式服务器IP
        '218.32.37.148'     // MyCard测试服务器IP
    ];


    public function getData()
    {
        if ($this->httpRequest->get('DATA')) {
            $this->getNotifyParams();
        }
        else {
            $this->getReturnParams();
        }
        return [
            'code'          => $this->getParameter('code'),
            'message'       => $this->getParameter('message'),
            'transactionId' => $this->getTransactionId(),
            'raw'           => $this->getParameter('raw')
        ];
    }


    public function sendData($data)
    {
        // TODO :: 查询并确认交易
        return $this->response = new NotificationResponse($this, $data);
    }


    /**
     * 客户端Return
     * Docs: Version1.9.1#3.2.4 - 回傳參數說明
     */
    private function getReturnParams()
    {
        $ReturnCode = $this->httpRequest->get('ReturnCode');        // 1 为成功, 其他则为失败. 注意: ReturnCode 为1并不代表交易成功，正确交易结果请参考PayResult
        $ReturnMsg = $this->httpRequest->get('ReturnMsg');
        $PayResult = $this->httpRequest->get('PayResult');          // 交易结果代码 交易成功为 3; 交易失败为 0
        $FacTradeSeq = $this->httpRequest->get('FacTradeSeq');      // 厂商交易序号
        $PaymentType = $this->httpRequest->get('PaymentType');      // 付费方式
        $Amount = $this->httpRequest->get('Amount');
        $Currency = $this->httpRequest->get('Currency');
        $MyCardType = $this->httpRequest->get('MyCardType');        // 通路代码 PaymentType = INGAME 时才有值
        $PromoCode = $this->httpRequest->get('PromoCode');          // 活动代码
        $Hash = $this->httpRequest->get('Hash');                    // 验证码
        // 1.PaymentType=INGAME时，传MyCard卡片号码; 2.PaymentType=COSTPOINT时，传会员扣点交易序号，格式为MMS开头+数; 3.其余PaymentType为Billing小额付款交易，传Billing交易序号
        $MyCardTradeNo = $this->httpRequest->get('MyCardTradeNo');


        // 检查
        if ($ReturnCode != 1) {
            throw new DefaultException($ReturnMsg);
        }
        if ($PayResult != 3) {
            throw new DefaultException($ReturnMsg);
        }

        // 签名验证
        if ($this->createSign('returnHash') != $this->httpRequest->get('Hash')) {
            throw new DefaultException('Sign Error');
        }


        $this->setParameter('code', $ReturnCode);
        $this->setParameter('message', $ReturnMsg);
        $this->setParameter('raw', $_REQUEST);
        $this->setTransactionId($FacTradeSeq);
    }


    /**
     * 服务端Notify
     * Docs: Version1.9.1#3.6 - MyCard主動通知CP廠商交易成功
     * 格式 DATA={"ReturnCode":"1","ReturnMsg":"QueryOK","FacServiceId":"MyCardSDK","TotalNum":2,"FacTradeSeq":["FacTradeSeq0001","FacTradeSeq0002"]}
     */
    private function getNotifyParams()
    {
        if (!in_array($this->httpRequest->getClientIp(), $this->ip)) {
            throw new DefaultException('IP Is Not Allowed');
        }
        $data = $this->httpRequest->get('DATA');
        try {
            $data = json_decode($data, true);
        } catch (DefaultException $e) {
            throw $e;
        }

        // 检查参数
        if (empty($data['ReturnCode'])) {
            throw new DefaultException('Missing MyCard ReturnCode');
        }
        if (empty($data['ReturnMsg'])) {
            throw new DefaultException('Missing MyCard ReturnMsg');
        }
        if (empty($data['FacServiceId'])) {
            throw new DefaultException('Missing MyCard FacServiceId');
        }
        if ($this->getAppId() != $data['FacServiceId']) {
            throw new DefaultException('Factory Service Id Not Matched');
        }
        if ($data['ReturnCode'] != 1) {
            throw new DefaultException($data['ReturnMsg']);
        }

        $this->setParameter('code', $data['ReturnCode']);
        $this->setParameter('message', $data['ReturnMsg']);
        $this->setParameter('raw', $data);
        $this->setTransactionId($data['FacTradeSeq']);
    }


}