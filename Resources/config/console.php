<?php

use Lexik\Bundle\CronFileGeneratorBundle\Command\GenerateCronCommand;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $container): void {
    $container->services()
        ->set('lexik_bundle_cron_file_generator.command.generate_cron_command', GenerateCronCommand::class)
            ->public()
            ->args([service('lexik_bundle_cron_file_generator.cron.dump_file_factory')])
            ->tag('console.command');
};
