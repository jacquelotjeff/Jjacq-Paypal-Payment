<?php

namespace Jjacq\PaypalPayment\Paypal;

use Symfony\Component\Config\Definition\Exception\Exception;

class Auth
{
    private $paypal;

    public function __construct(Paypal $paypal)
    {
        $this->paypal = $paypal;
    }

    public function getToken()
    {
        $url = Paypal::getBaseUrl().'v1/oauth2/token';

        $jsonResponse = $this->paypal->makeCall($url, 'grant_type=client_credentials', true);

        if (!empty($jsonResponse->error)) {
            throw new Exception("Error when trying to get token : ".$jsonResponse->error_description);
        }

        return $jsonResponse->access_token;
    }

    /**
     * @return Paypal
     */
    public function getPaypal()
    {
        return $this->paypal;
    }
}