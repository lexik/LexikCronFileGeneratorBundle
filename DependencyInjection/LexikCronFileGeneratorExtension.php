<?php

namespace Lexik\Bundle\CronFileGeneratorBundle\DependencyInjection;

use Lexik\Bundle\CronFileGeneratorBundle\Cron\DumpFile;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

class LexikCronFileGeneratorExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

        $loader->load('dump_file.xml');
        $loader->load('console.xml');

        $container->setParameter('lexik_cron_file_generator.cron.configuration_arguments', $config);
        $container->setAlias(DumpFile::class, 'lexik_cron_file_generator.cron.dump_file');
    }
}
