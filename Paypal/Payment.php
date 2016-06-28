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

    public function sendPayment($params = [])
    {
        $params = array_merge($this->getParameters(), $params);
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