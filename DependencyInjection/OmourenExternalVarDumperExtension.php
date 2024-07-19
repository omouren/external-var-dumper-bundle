<?php

namespace Omouren\ExternalVarDumperBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * @author Olivier Mouren <mouren.olivier@gmail.com>
 */
class OmourenExternalVarDumperExtension extends Extension
{
    private $enabled = false;

    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        $this->enabled = $config['enabled'];
    }

    public function isEnabled()
    {
        return $this->enabled;
    }
}
