<?php

namespace Wunderdata\MailchimpBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class WunderdataMailchimpExtension extends Extension
{

    /**
     * Loads a specific configuration.
     *
     * @param array $config    An array of configuration values
     * @param ContainerBuilder $container A ContainerBuilder instance
     */
    public function load(array $config, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader(
            $container,
            new FileLocator(__DIR__.'/../Resources/config')
        );
        $loader->load('services.xml');

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $config);

        $container->setParameter('wunderdata_mailchimp.apikey', $config['apikey']);
        if (!empty($config['opts'])) {
            $container->setParameter('wunderdata_mailchimp.opts', $config['opts']);
        } else {
            $container->setParameter('wunderdata_mailchimp.opts', array());
        }
    }
}