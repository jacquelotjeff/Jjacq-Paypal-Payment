<?php

namespace Jjacq\PaypalPayment\Paypal;

class Payment
{
    private $auth;
    private $paypal;

    private $amount = 0;
    private $currency = "EUR";
    private $description;
    private $intent = "sale";

    private $noteToPayer;

    public function __construct(Auth $auth)
    {
        $this->auth   = $auth;
        $this->paypal = $this->auth->getPaypal();
    }

    private function getParameters()
    {
        return [
            'amount'        => $this->amount,
            'currency'      => $this->currency,
            'description'   => $this->description,
            'intent'        => $this->intent,
            'note_to_payer' => $this->noteToPayer,
        ];
    }

    /**
     * Send payment to paypal
     * @param array $params
     * @return array
     */
    public function sendPayment($params = [])
    {
        $params = array_merge($this->getParameters(), $params);
        $url = $this->paypal->getBaseUrl().'v1/payments/payment';

        $paramsToSend = array(
            'intent' => $params['intent'],
            'redirect_urls' => [
                'return_url' => $this->paypal->getReturnUrl(),
                'cancel_url' => $this->paypal->getCancelUrl()
            ],
            'payer'  => array(
                'payment_method' => 'paypal'
            ),
            'transactions' => array(
                array(
                    'amount' => array(
                        'total'    => $params['amount'],
                        'currency' => $params['currency']
                    ),
                    'description' => $params['description']
                )
            )
        );

        $httpHeader = array(
            'Content-Type: application/json',
            'Authorization: Bearer '.$this->auth->getToken()
        );

        $json = json_encode($paramsToSend);
        $jsonResponse = $this->paypal->makeCall($url, $json, true, $httpHeader);

        foreach ($jsonResponse['links'] as $link) {
            if($link['rel'] == 'self') {
                $paymentDetailUrl = $link['href'];
                $paymentDetailMethod = $link['method'];
            }
            if($link['rel'] == 'approval_url') {
                $redirectPaypalApproval = $link['href'];
            }
        }

        return [
            'paymentDetailUrl'       => $paymentDetailUrl,
            'paymentDetailMethod'    => $paymentDetailMethod,
            'redirectPaypalApproval' => $redirectPaypalApproval
        ];

    }

    /**
     * Execute a payment
     * @param $paymentId
     * @param $token
     * @param $payerId
     *
     * @return mixed
     */
    public function executePayment($paymentId, $token, $payerId)
    {
        $url = $this->paypal->getBaseUrl().'v1/payments/payment/'.$paymentId.'/execute/';

        $httpHeader = array(
            'Content-Type: application/json',
            'Authorization: Bearer '.$this->auth->getToken()
        );

        $paramsToSend = [ 'payer_id' => $payerId ];
        $json = json_encode($paramsToSend);

        $response = $this->paypal->makeCall($url, $json, true, $httpHeader);

        return $response;
    }
    /**
     * @param int $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    /**
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param string $currency
     *
     * @return Payment
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return Payment
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string
     */
    public function getIntent()
    {
        return $this->intent;
    }

    /**
     * @param string $intent
     *
     * @return Payment
     */
    public function setIntent($intent)
    {
        $this->intent = $intent;

        return $this;
    }

    /**
     * @return string
     */
    public function getNoteToPayer()
    {
        return $this->noteToPayer;
    }

    /**
     * @param string $noteToPayer
     *
     * @return Payment
     */
    public function setNoteToPayer($noteToPayer)
    {
        $this->noteToPayer = $noteToPayer;

        return $this;
    }
}