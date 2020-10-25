<?php

namespace Pusha\LaravelWebMoney\Interfaces\X2;

use Pusha\LaravelWebMoney\WMSigner;

class WMRequestX2
{
    /**
     * Owner WMID.
     *
     * @var string
     */
    private $WMID;

    /**
     * Sender’s WM purse number.
     *
     * @var string
     */
    private $payer;

    /**
     * Key.kwm to bin2hex() or path to the key.
     *
     * @var string
     */
    private $key;

    /**
     * The password for the key.
     *
     * @var string
     */
    private $password;

    /**
     * Request number. A positive integer of 15 digits maximally; must be greater than a previous payment
     * request number! Each WMID that signs the request is linked to its` unique sequence of monotonically
     * increasing values of this parameter.
     *
     * @var string
     */
    private $reqn;

    /**
     * Recipient’s purse number.
     *
     * @var string
     */
    private $payee;

    /**
     * Amount of the sum transferred.
     *
     * @var float
     */
    private $amount;

    /**
     * Description. Arbitrary string of 0 to 255 characters.
     *
     * @var string
     */
    private $description;

    /**
     * Transaction number. Transaction number in the sender's accounting system;
     * any positive integer; must be unique for the WMID that signs the request.
     *
     * @var integer
     */
    private $tranID;

    /**
     * Protection period. Maximum protection period allowed in days;
     * An integer in the range 0 - 120; 0 - means that no protection will be used;
     *
     * @var integer
     */
    private $period   = 0;

    /**
     * Protected payment. Arbitrary string of 5 to 255 characters.
     * No spaces may be used at the beginning or the end;
     *
     * @var string
     */
    private $pcode    = '';

    /**
     * Invoice number (in the WebMoney system). An integer > 0;
     * 0 means that the transfer is made without an invoice.
     * Maximum is 2 32 -1;
     *
     * @var integer
     */
    private $wminvid  = 0;

    /**
     * Consider recipient authorization.
     *
     * @var integer
     */
    private $onlyauth = 1;

    /**
     * WMRequestX2 constructor.
     * @param $data
     */
    public function __construct($data)
    {
        $this->WMID        = $data['WMID'];
        $this->payer       = $data['payer'];
        $this->key         = $data['key'];
        $this->password    = $data['password'];

        $this->reqn        = $data['reqn'];
        $this->payee       = $data['payee'];
        $this->amount      = $data['amount'];
        $this->description = $data['description'];
        $this->tranID      = $data['tranID'];

        $this->period      = 0;
        $this->pcode       = '';
        $this->wminvid     = 0;
        $this->onlyauth    = 1;
    }

    /**
     * Sends a request and checks the data.
     *
     * @return int|string
     */
    public function validate()
    {
        $response = simplexml_load_string($this->getResponse());

        if(!empty($response))
        {
            if(intval($response->retval) === 0)
            {
                return 0;
            }
            else
            {
                $result['retval']  = intval($response->retval);
                $result['retdesc'] = strval($response->retdesc);
                $result['date']    = strval($response->operation->datecrt);

                return $result;
            }
        }
        else
        {
            return 'Response is not received...';
        }
    }

    /**
     * Generates the signature for data.
     *
     * @return string
     * @throws \Exception
     */
    private function getSign()
    {
        $signer = new WMSigner($this->WMID, $this->key, $this->password);

        $data = $this->reqn.
                $this->tranID.
                $this->payer.
                $this->payee.
                $this->amount.
                $this->period.
                $this->pcode.
                mb_convert_encoding($this->description, 'Windows-1251', 'UTF-8').
                $this->wminvid;

        return $signer->sign($data);
    }

    /**
     * Generates XML.
     *
     * @return string
     */
    private function getXML()
    {
        $xml  = '<w3s.request>';
        $xml .= '<reqn>'.$this->reqn.'</reqn>';
        $xml .= '<wmid>'.$this->WMID.'</wmid>';
        $xml .= '<sign>'.$this->getSign().'</sign>';
        $xml .= '<trans>';
        $xml .= '<tranid>'.$this->tranID.'</tranid>';
        $xml .= '<pursesrc>'.$this->payer.'</pursesrc>';
        $xml .= '<pursedest>'.$this->payee.'</pursedest>';
        $xml .= '<amount>'.$this->amount.'</amount>';
        $xml .= '<period>'.$this->period.'</period>';
        $xml .= '<pcode>'.$this->pcode.'</pcode>';
        $xml .= '<desc>'.htmlspecialchars($this->description, ENT_QUOTES).'</desc>';
        $xml .= '<wminvid>'.$this->wminvid.'</wminvid>';
        $xml .= '<onlyauth>'.$this->onlyauth.'</onlyauth>';
        $xml .= '</trans>';
        $xml .= '</w3s.request>';

        return $xml;
    }

    /**
     * Sends and receives a reply from WebMoney.
     *
     * @return mixed
     */
    private function getResponse()
    {
        $ch = curl_init('https://w3s.webmoney.ru/asp/XMLTrans.asp');

        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_POST,1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $this->getXML());

        curl_setopt($ch, CURLOPT_CAINFO, __DIR__ . '/WMunited.cer');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, TRUE);

        $result = curl_exec($ch);

        return $result;
    }
}
