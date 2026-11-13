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
            'prod',
        ],
        'user' => [
            'staging' => 'project_staging',
            'prod' => 'project_prod',
        ],
        'php_version' => 'php7.3',
        'absolute_path' => [
            'staging' => 'path/to/staging',
            'prod' => 'path/to/prod',
        ],
        'output_path' => '%kernel.cache_dir%/cron_test',
        'crons' => [
            [
                'name' => 'send email',
                'command' => 'app:test',
                'env' => [
                    'staging' => '* * * * *',
                    'prod' => '* 5 * * *',
                ],
            ],
        ],
    ]);
};
