# A Paypal Payment Bundle for Symfony

This plugin creates a smart bridge between your website and Paypal.
You can also define all parameters in your administration page.

## Installation

### Install with composer

```php
composer require jjacq/paypal-payment
```

### Add this line in your AppKernel

```php
new Jjacq\PaypalPayment\PaypalPaymentBundle(),
```

### Basic configuration

Add this lines in your app/config/config.yml file.

```yaml
paypal_payment:
    client_id: "your-customer-key"
    client_password: "your-customer-password"
```
