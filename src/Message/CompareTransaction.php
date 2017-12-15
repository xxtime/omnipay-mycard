<?php
/**
 * 差异比较
 */

namespace Omnipay\MyCard\Message;


use DateTime;
use DateTimeZone;
use Omnipay\MyCard\Exception\DefaultException;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

class CompareTransaction
{

    protected $data;


    protected $allowedIp = [
        '210.71.189.165',
        '218.32.37.148',
    ];


    protected $httpRequest;


    public function __construct()
    {
        $this->httpRequest = HttpRequest::createFromGlobals();
        if (!in_array($this->httpRequest->getClientIp(), $this->allowedIp)) {
            $this->error();
        }
    }


    public function getParams()
    {
        return [
            'card'      => $this->httpRequest->get('MyCardTradeNo'),
            'startTime' => strtotime($this->httpRequest->get('StartDateTime')),
            'endTime'   => strtotime($this->httpRequest->get('EndDateTime')),
        ];
    }


    /**
     * 设置数据
     * INGAME: 卡片儲值; COSTPOINT: 會員扣點; (Billing): 其他代碼為小額付費之付費方式
     * $data = [
     *     [
     *          'type'                 => 'INGAME',
     *          'transactionId'        => '厂商交易号',
     *          'transactionReference' => 'MyCard交易号',
     *          'card'                 => 'MC341432533',
     *          'amount'               => 50,
     *          'currency'             => 'TWD',
     *          'account'              => '888888',
     *          'time'                 => 1500000000,
     *     ]
     * ]
     *
     * @param array $data
     * @return $this
     */
    public function setData(array $data = [])
    {
        $dateTime = new DateTime();
        $dateTime->setTimezone(new DateTimeZone('Asia/Taipei'));

        foreach ($data as $value) {
            $dateTime->setTimestamp($value['time']);
            $this->data .=
                $value['type'] . ',' .
                $value['transactionReference'] . ',' .
                $value['card'] . ',' .
                $value['transactionId'] . ',' .
                $value['account'] . ',' .
                $value['amount'] . ',' .
                $value['currency'] . ',' .
                $dateTime->format('Y-m-d\TH:i:s') .
                "<BR>\r\n";
        };
        return $this;
    }


    public function send()
    {
        exit($this->data);
    }


    public function error()
    {
        throw new DefaultException('IP Not Allowed');
    }


}