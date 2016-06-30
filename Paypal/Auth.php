<?php

namespace Jjacq\PaypalPayment\Paypal;

use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;

class Auth
{
    private $paypal;

    public function __construct(Paypal $paypal)
    {
        $this->paypal = $paypal;
    }

    /**
     * Get the token
     * @return JsonResponse
     */
    public function getToken()
    {
        $url = $this->paypal->getBaseUrl().'v1/oauth2/token';

        $jsonResponse = $this->paypal->makeCall($url, 'grant_type=client_credentials', true);

        if (!empty($jsonResponse->error)) {
            throw new Exception("Error when trying to get token : ".$jsonResponse->error_description);
        }
        
        return $jsonResponse['access_token'];
    }

    /**
     * @return Paypal
     */
    public function getPaypal()
    {
        return $this->paypal;
    }
}