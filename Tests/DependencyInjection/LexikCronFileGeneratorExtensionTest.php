<?php

namespace Lexik\Bundle\CronFileGeneratorBundle\Tests\DependencyInjection;

use Lexik\Bundle\CronFileGeneratorBundle\Command\GenerateCronCommand;
use Lexik\Bundle\CronFileGeneratorBundle\Cron\Configuration;
use Lexik\Bundle\CronFileGeneratorBundle\Cron\DumpFile;
use Lexik\Bundle\CronFileGeneratorBundle\Cron\DumpFileFactory;
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
use Symfony\Component\DependencyInjection\Kernel\ServicesBundle;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\Kernel;

class LexikCronFileGeneratorExtensionTest extends TestCase
{
    public function testLoadConfiguration()
    {
        $container = $this->createContainer([
            'framework' => [
                'secret' => 'testing',
                'ide' => null,
                'http_method_override' => false,
            ],
            'twig' => [
                'strict_variables' => true,
            ],
            'lexik_cron_file_generator' => $this->getBundleConfig(),
        ]);

        $container
            ->register('autowired', Autowired::class)
            ->setPublic(true)
            ->setAutowired(true);

        $this->compileContainer($container);

        $this->assertInstanceOf(Autowired::class, $container->get('autowired'));
        $this->assertInstanceOf(DumpFile::class, $container->get('autowired')->getDumpFile());
    }

    public function testServiceDefinitions()
    {
        $container = new ContainerBuilder();
        (new LexikCronFileGeneratorExtension())->load([$this->getBundleConfig()], $container);

        $dumpFileFactory = $container->getDefinition('lexik_bundle_cron_file_generator.cron.dump_file_factory');
        $this->assertSame(DumpFileFactory::class, $dumpFileFactory->getClass());
        $this->assertTrue($dumpFileFactory->isPublic());
        $this->assertReferences(['lexik_cron_file_generator.cron.dump_file'], $dumpFileFactory->getArguments());

        $dumpFile = $container->getDefinition('lexik_cron_file_generator.cron.dump_file');
        $this->assertSame(DumpFile::class, $dumpFile->getClass());
        $this->assertTrue($dumpFile->isPublic());
        $this->assertReferences(['twig', 'lexik_cron_file_generator.cron.configuration'], $dumpFile->getArguments());

        $configuration = $container->getDefinition('lexik_cron_file_generator.cron.configuration');
        $this->assertSame(Configuration::class, $configuration->getClass());
        $this->assertTrue($configuration->isPrivate());
        $this->assertSame(['%lexik_cron_file_generator.cron.configuration_arguments%'], $configuration->getArguments());

        $command = $container->getDefinition('lexik_bundle_cron_file_generator.command.generate_cron_command');
        $this->assertSame(GenerateCronCommand::class, $command->getClass());
        $this->assertTrue($command->isPublic());
        $this->assertReferences(['lexik_bundle_cron_file_generator.cron.dump_file_factory'], $command->getArguments());
        $this->assertSame([[]], $command->getTag('console.command'));

        $this->assertSame('lexik_cron_file_generator.cron.dump_file', (string) $container->getAlias(DumpFile::class));
    }

    private function assertReferences(array $expectedIds, array $arguments): void
    {
        $this->assertCount(\count($expectedIds), $arguments);

        foreach ($expectedIds as $index => $expectedId) {
            $this->assertInstanceOf(Reference::class, $arguments[$index]);
            $this->assertSame($expectedId, (string) $arguments[$index]);
        }
    }

    private function getBundleConfig(): array
    {
        return [
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
        ];
    }

    private function createContainer(array $configs = []): ContainerBuilder
    {
        $container = new ContainerBuilder(new ParameterBag([
            'kernel.cache_dir' => __DIR__,
            'kernel.root_dir' => __DIR__,
            'kernel.project_dir' => __DIR__,
            'kernel.build_dir' => __DIR__,
            'kernel.share_dir' => __DIR__,
            'kernel.runtime_environment' => 'test',
            'kernel.runtime_mode.web' => false,
            'kernel.runtime_mode.cli' => true,
            'kernel.charset' => 'UTF-8',
            'kernel.environment' => 'test',
            'kernel.debug' => false,
            'kernel.bundles_metadata' => [],
            'kernel.container_class' => 'AutowiringTestContainer',
            'kernel.bundles' => [
                'FrameworkBundle' => FrameworkBundle::class,
                'LexikCronFileGeneratorBundle' => LexikCronFileGeneratorBundle::class,
            ],
            'env(base64:default::SYMFONY_DECRYPTION_SECRET)' => 'dummy',
            'env(bool:default::SYMFONY_TRUST_X_SENDFILE_TYPE_HEADER)' => false,
            'env(default::SYMFONY_TRUSTED_HOSTS)' => [],
            'env(default::SYMFONY_TRUSTED_HEADERS)' => [],
            'env(default::SYMFONY_TRUSTED_PROXIES)' => null,
        ]));

        $container->set(
            'kernel',
            new class('test', false) extends Kernel {
                public function registerBundles(): iterable
                {
                    return [];
                }

                public function registerContainerConfiguration(LoaderInterface $loader): void
                {
                }
            }
        );

        // Since Symfony 8.1, core services (event_dispatcher, ...) are provided by ServicesBundle
        if (class_exists(ServicesBundle::class)) {
            $configs = ['services' => []] + $configs;
            $container->registerExtension((new ServicesBundle())->getContainerExtension());
        }

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
        if (class_exists(ServicesBundle::class)) {
            (new ServicesBundle())->build($container);
        }

        (new TwigBundle())->build($container);

        $container->compile();
    }
}
