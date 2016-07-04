<?php

namespace Jjacq\PaypalPaymentBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class PaypalPaymentExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config        = $this->processConfiguration($configuration, $configs);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');

        $container->setParameter('paypal_payment', $config);
        $container->setParameter('paypal_payment.paypal_class', $config['paypal_class']);
        $container->setParameter('paypal_payment.payment_class', $config['payment_class']);
        $container->setParameter('paypal_payment.auth_class', $config['auth_class']);
    }
}
