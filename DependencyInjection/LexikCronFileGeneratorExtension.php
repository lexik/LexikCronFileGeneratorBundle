<?php

namespace Lexik\Bundle\CronFileGeneratorBundle\DependencyInjection;

use Lexik\Bundle\CronFileGeneratorBundle\Cron\DumpFile;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;

class LexikCronFileGeneratorExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
        $loader = new PhpFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

        $loader->load('dump_file.php');
        $loader->load('console.php');

        $container->setParameter('lexik_cron_file_generator.cron.configuration_arguments', $config);
        $container->setAlias(DumpFile::class, 'lexik_cron_file_generator.cron.dump_file');
    }
}
