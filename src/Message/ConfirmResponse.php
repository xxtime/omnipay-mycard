<?php

namespace Omnipay\MyCard\Message;


/*
 *
 * 确认返回格式:
 * array[
 *	  "ReturnCode" => "MBP006"
 *	  "ReturnMsg" => "查無授權交易,交易狀態可能不符合"
 *	  "FacTradeSeq" => ""
 *	  "TradeSeq" => ""
 *	  "MyCardTradeNo" => null
 *	  "SerialId" => ""
 * ]
 *
 */
use Omnipay\Common\Message\AbstractResponse;

class ConfirmResponse extends AbstractResponse
{

    // 二次确认会失败 ReturnCode=MBP006
    public function isSuccessful()
    {
        return ($this->getData()['ReturnCode'] == 1) ? true : false;
    }


    public function isPaid()
    {
        return $this->isSuccessful();
    }


    public function getTransactionId()
    {
        return $this->getData()['FacTradeSeq'];
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