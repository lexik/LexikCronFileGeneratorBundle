<?php

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $container): void {
    $container->extension('framework', [
        'secret' => 'test',
        'test' => true,
        'http_method_override' => false,
    ]);

    $container->extension('twig', [
        'strict_variables' => false,
        'default_path' => './Resources/views',
    ]);

    $container->extension('lexik_cron_file_generator', [
        'env_available' => [
            'staging',
        ],
        'user' => [
            'staging' => 'project_staging',
        ],
        'php_version' => 'php7.3',
        'absolute_path' => [
            'staging' => 'path/to/staging',
        ],
        'output_path' => '%kernel.cache_dir%/cron_test',
    ]);
};
