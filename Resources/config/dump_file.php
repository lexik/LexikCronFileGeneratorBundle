<?php

use Lexik\Bundle\CronFileGeneratorBundle\Cron\Configuration;
use Lexik\Bundle\CronFileGeneratorBundle\Cron\DumpFile;
use Lexik\Bundle\CronFileGeneratorBundle\Cron\DumpFileFactory;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use function Symfony\Component\DependencyInjection\Loader\Configurator\param;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $container): void {
    $container->services()
        ->set('lexik_bundle_cron_file_generator.cron.dump_file_factory', DumpFileFactory::class)
            ->public()
            ->args([service('lexik_cron_file_generator.cron.dump_file')])

        ->set('lexik_cron_file_generator.cron.dump_file', DumpFile::class)
            ->public()
            ->args([service('twig'), service('lexik_cron_file_generator.cron.configuration')])

        ->set('lexik_cron_file_generator.cron.configuration', Configuration::class)
            ->private()
            ->args([param('lexik_cron_file_generator.cron.configuration_arguments')]);
};
