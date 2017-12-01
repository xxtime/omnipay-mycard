<?php
/**
 * 差异比较
 */

namespace Omnipay\MyCard\Message;


class compareTransaction
{

    protected $data;


    /**
     * 参考格式:
     *
     * $data = [
     *     [
     *          'transactionId'        => '厂商交易号',
     *          'transactionReference' => 'MyCard交易号',
     *          'card'                 => 'MC341432533',
     *          'amount'               => 50,
     *          'currency'             => 'TWD',
     *          'account'              => '888888',
     *          'time'                 => 1500000000,
     *     ]
     * ]
     * @param array $data
     */
    public function setData(array $data = [])
    {
        // TODO :: INGAME and 时区问题
        foreach ($data as $value) {
            $this->data .= "INGAME," .
                $value['transactionReference'] . ',' .
                $value['card'] . ',' .
                $value['transactionId'] . ',' .
                $value['account'] . ',' .
                $value['amount'] . ',' .
                $value['currency'] . ',' .
                date('Y-m-d\TH:i:s', $value['time']) .
                '<BR>';
        };
    }


    public function output()
    {
        exit($this->data);
    }


}