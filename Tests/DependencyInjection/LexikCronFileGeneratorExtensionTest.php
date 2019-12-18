<?php

namespace Lexik\Bundle\CronFileGeneratorBundle\Tests\DependencyInjection;

use Lexik\Bundle\CronFileGeneratorBundle\Cron\DumpFile;
use Lexik\Bundle\CronFileGeneratorBundle\DependencyInjection\LexikCronFileGeneratorExtension;
use Lexik\Bundle\CronFileGeneratorBundle\LexikCronFileGeneratorBundle;
use Lexik\Bundle\CronFileGeneratorBundle\Tests\Stubs\Autowired;
use Lexik\Bundle\CronFileGeneratorBundle\Tests\TestCase;
use Symfony\Bundle\FrameworkBundle\DependencyInjection\FrameworkExtension;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\TwigBundle\DependencyInjection\TwigExtension;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;
use Symfony\Component\HttpKernel\Kernel;

class LexikCronFileGeneratorExtensionTest extends TestCase
{
    public function testLoadConfiguration()
    {
        $container = $this->createContainer([
            'framework' => [
                'secret' => 'testing',
                ],
            'twig' => [
                'strict_variables' => true,
                'exception_controller' => null, // to be removed in 5.0
            ],
            'lexik_cron_file_generator' => [
                'env_available' => [
                    'staging', 'prod',
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
                'output_path' => 'path/to/cron_file',
                'crons' => [
                    [
                        'name' => 'test',
                        'command' => 'app:test',
                        'env' => [
                            'staging' => '* * * * *',
                            'prod' => '* 5 * * *',
                        ],
                    ],
                ],
            ],
        ]);

        $container
            ->register('autowired', Autowired::class)
            ->setPublic(true)
            ->setAutowired(true);

        $this->compileContainer($container);

        $this->assertInstanceOf(Autowired::class, $container->get('autowired'));
        $this->assertInstanceOf(DumpFile::class, $container->get('autowired')->getDumpFile());
    }

    private function createContainer(array $configs = [])
    {
        $container = new ContainerBuilder(new ParameterBag([
            'kernel.cache_dir' => __DIR__,
            'kernel.root_dir' => __DIR__,
            'kernel.project_dir' => __DIR__,
            'kernel.charset' => 'UTF-8',
            'kernel.environment'      => 'test',
            'kernel.debug' => false,
            'kernel.bundles_metadata' => [],
            'kernel.container_class'  => 'AutowiringTestContainer',
            'kernel.bundles' => [
                'FrameworkBundle' => FrameworkBundle::class,
                'LexikCronFileGeneratorBundle' => LexikCronFileGeneratorBundle::class,
            ],
            'env(base64:default::SYMFONY_DECRYPTION_SECRET)' => 'dummy',
        ]));

        $container->set(
            'kernel',
            new class ('test', false) extends Kernel
            {
                public function registerBundles()
                {
                }

                public function registerContainerConfiguration(LoaderInterface $loader)
                {
                }
            }
        );

        $container->registerExtension(new FrameworkExtension());
        $container->registerExtension(new TwigExtension());
        $container->registerExtension(new LexikCronFileGeneratorExtension());

        foreach ($configs as $extension => $config) {
            $container->loadFromExtension($extension, $config);
        }


        return $container;
    }

    private function compileContainer(ContainerBuilder $container)
    {
        (new TwigBundle())->build($container);

        $container->compile();
    }
}
