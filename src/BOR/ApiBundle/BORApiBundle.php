<?php

namespace BOR\ApiBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use BOR\ApiBundle\DependencyInjection\Security\Factory\WsseFactory;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class BORApiBundle
 *
 * @package BOR\ApiBundle
 */
class BORApiBundle extends Bundle
{
    /**
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $extension = $container->getExtension('security');
        $extension->addSecurityListenerFactory(new WsseFactory());
    }
}
