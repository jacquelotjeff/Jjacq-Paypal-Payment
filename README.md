# A Paypal Payment Bundle for Symfony

This plugin creates a smart bridge between your website and Paypal.

You can also define all parameters in your config file.

## Important

This bundle allows you to send payments to Paypal only via paypal method
So you can't use this bundle to send payments via credit_card method
## Installation

### Install with composer

```php
composer require jjacq/paypal-payment
```

### Add this line in your AppKernel

```php
new Jjacq\PaypalPayment\PaypalPaymentBundle(),
```

### Minimum configuration

Add these lines in your app/config/config.yml file.

```yaml
paypal_payment:
    client_id: "your-customer-key"
    client_password: "your-customer-password"
    return_url: http://your-project-url.com/app_dev.php/success
    cancel_url: http://your-project-url.com/app_dev.php/error
```
### Advanced configuration

```yaml
paypal_payment:
    client_id: "your-customer-key"
    client_password: "your-customer-password"
    return_url: http://your-project-url.com/app_dev.php/success
    cancel_url: http://your-project-url.com/app_dev.php/error
    mode: "live" # Default is 'sandbox'
    verbose_mode: true # Default is false /!\ Not efficient in live mode
    log_dir: "/var/logs/paypal_payments/"
    paypal_class: "Jjacq\PaypalPayment\Paypal\Paypal"
    auth_class: "Jjacq\PaypalPayment\Paypal\Auth"
    payment_class: "Jjacq\PaypalPayment\Paypal\Payment"
```
### Basic usage

```php
<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{

    /**
     * @Route("/", name="payment")
     */
    public function indexAction(Request $request)
    {

        $payment = $this->get('paypal_payment.payment');
           
        // Send the payment to get approval url
        $paymentSend = $payment->sendPayment([
            'amount' => "20.20",
            'currency' => 'EUR',
            'description' => 'Description of your payment',
            'intent' => 'sale',
            'note_to_payer' => 'Note to payer',
        ]);

        // Redirect to Paypal approval url
        return $this->redirect($paymentSend['redirectPaypalApproval']);
    }
    
    /**
     * @Route("/success", name="success")
     */
    public function successAction(Request $request)
    {
        $paymentId = $request->get('paymentId');
        $token     = $request->get('token');
        $payerId   = $request->get('PayerID');
        
        // This url is not accessible if there are no paypal informations
        if (empty($paymentId) || empty($token) || empty($payerId)) {
            return $this->redirectToRoute('homepage');
        }

        $payment = $this->get('paypal_payment.payment');
        // Validate the payment (execution)
        $informationsPayment = $payment->executePayment($paymentId, $token, $payerId);

        // Now, you can access payment informations
        // Check if payment was executed ...
        dump($informationsPayment);
        
        // You can also Store informations in your database ...
        // If payment was executed
        
        die('Payment success');
    }

    /**
     * @Route("/error", name="error")
     */
    public function errorAction()
    {
        die('Payment error');
    }
}
```
