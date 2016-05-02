<?php

namespace Jjacq\PaypalPayment;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class PaypalPayment extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
    }
}
