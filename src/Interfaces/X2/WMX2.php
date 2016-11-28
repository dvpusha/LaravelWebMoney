<?php

namespace Pusha\LaravelWebMoney;

use Illuminate\Support\Facades\Config;

class WMX2
{
    /**
     * Data for payment.
     *
     * @var array
     */
    private $data = [];

    /**
     * WMX2 constructor.
     * @param $data
     */
    public function __construct($data)
    {
        $this->data = [
            'reqn'        => $data['reqn'],
            'payee'       => $data['payee'],
            'amount'      => floatval($data['amount']),
            'description' => trim($data['description']),
            'tranID'      => $data['tranID'],
            'WMID'        => Config::get('webmoney.X2.WMID'),
            'payer'       => Config::get('webmoney.X2.payer'),
            'key'         => Config::get('webmoney.X2.key'),
            'password'    => Config::get('webmoney.X2.password'),
        ];
    }

    /**
     * Attempt to withdraw money.
     *
     * @return int|string
     */
    public function withdraw()
    {
        $withdraw = new WMRequestX2($this->data);

        return $withdraw->validate();
    }
}