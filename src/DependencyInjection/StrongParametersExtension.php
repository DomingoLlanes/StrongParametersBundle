<?php

namespace DomingoLlanes\StrongParametersBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @link http://symfony.com/doc/current/cookbook/bundles/extension.html
 */
class StrongParametersExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $strongParametersResource = array_key_exists('strong_parameters', $config) ?
            $config['strong_parameters']['resource'] :
            "%kernel.project_dir%/app/Resources/parameters/";
        $strongParametersException = array_key_exists('strong_parameters', $config) ?
            $config['strong_parameters']['exception'] :
            false;

        $container->setParameter('strong_parameters.resource', $strongParametersResource);
        $container->setParameter('strong_parameters.exception', $strongParametersException);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
        //$loader->load('config.yml');
    }

    public function getAlias()
    {
        return 'strong_parameters';
    }
}
