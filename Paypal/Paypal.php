<?php

namespace Jjacq\PaypalPayment\Paypal;

use Symfony\Component\Serializer\Encoder\JsonDecode;

class Paypal
{
    const URL_SANDBOX = "https://api.sandbox.paypal.com/";
    const URL_LIVE = "https://api.paypal.com/";

    private $clientId;
    private $clientPassword;
    private $mode;
    private $verbose = false;
    private $logDir;
    private $cancelUrl;
    private $returnUrl;

    public function __construct(array $params)
    {
        $this->clientId       = $params['client_id'];
        $this->clientPassword = $params['client_password'];
        $this->mode           = $params['mode'];
        $this->logDir         = $params['log_dir'];
        $this->returnUrl      = !empty($params['return_url']) ? $params['return_url'] : null;
        $this->cancelUrl      = !empty($params['cancel_url']) ? $params['cancel_url'] : null;
        $this->verbose        = $params['verbose_mode'];
    }

    /**
     * @param string $url    URL TO CALL
     * @param string $params STRING OF PARAMS TO SEND WITH REQUEST
     * @param bool   $method METHOD POST|GET
     */
    public function makeCall($url, $params = '', $method = true)
    {
        $curl = curl_init($url);

        curl_setopt($curl, CURLOPT_POST, $method);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_USERPWD, $this->clientId.":".$this->clientPassword);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $params);

        if ($this->verbose == true && $this->mode == "sandbox") {
            curl_setopt($curl, CURLOPT_VERBOSE, true);
        }

        $response = curl_exec($curl);
        if (empty($response)) {
            // TODO send error in log file
            die(curl_error($curl));
            curl_close($curl);
        } else {
            $info = curl_getinfo($curl);
//            echo "Time took: " . $info['total_time']*1000 . "ms\n";
            curl_close($curl); // close cURL handler
//            if($info['http_code'] != 200 && $info['http_code'] != 201 ) {
//                echo "Received error: " . $info['http_code']. "\n";
//                echo "Raw response:".$response."\n";
//                die();
//            }
        }

        return json_decode($response);
    }

    static function getBaseUrl()
    {
        return self::getMode() == "sandbox" ? self::URL_SANDBOX : self::URL_LIVE;
    }

    /**
     * @return string
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * @return string
     */
    public function getClientPassword()
    {
        return $this->clientPassword;
    }

    /**
     * @return string
     */
    public function getMode()
    {
        return $this->mode;
    }

    /**
     * @return bool
     */
    public function getVerbose()
    {
        return $this->verbose;
    }

    /**
     * @return string
     */
    public function getLogDir()
    {
        return $this->logDir;
    }

    /**
     * @return string|null
     */
    public function getCancelUrl()
    {
        return $this->cancelUrl;
    }

    /**
     * @return string|null
     */
    public function getReturnUrl()
    {
        return $this->returnUrl;
    }
}