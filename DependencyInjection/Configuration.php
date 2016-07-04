<?php

namespace Jjacq\PaypalPaymentBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('paypal_payment');

        $rootNode
            ->addDefaultsIfNotSet()
            ->children()
                ->scalarNode('client_id')->isRequired()->cannotBeEmpty()->end()
                ->scalarNode('client_password')->isRequired()->cannotBeEmpty()->end()
                ->enumNode('mode')
                    ->values(array('sandbox', 'live'))
                    ->defaultValue('sandbox')
                    ->cannotBeOverwritten()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('return_url')->isRequired()->cannotBeEmpty()->end()
                ->scalarNode('cancel_url')->isRequired()->cannotBeEmpty()->end()
                ->booleanNode('verbose_mode')->defaultFalse()->end()
                ->scalarNode('log_dir')->defaultValue('/var/logs/paypal_payments/')->end()
                ->scalarNode('paypal_class')
                    ->defaultValue('Jjacq\PaypalPaymentBundle\Paypal\Paypal')
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('auth_class')
                    ->defaultValue('Jjacq\PaypalPaymentBundle\Paypal\Auth')
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('payment_class')
                    ->defaultValue('Jjacq\PaypalPaymentBundle\Paypal\Payment')
                    ->cannotBeEmpty()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
