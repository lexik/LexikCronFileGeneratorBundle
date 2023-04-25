<?php

namespace Lexik\Bundle\CronFileGeneratorBundle\Tests\Functional;

use Lexik\Bundle\CronFileGeneratorBundle\LexikCronFileGeneratorBundle;
use Lexik\Bundle\CronFileGeneratorBundle\Tests\Functional\Bundle\Bundle;
use Psr\Log\NullLogger;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel;

class AppKernel extends Kernel
{
    private string $config;

    public function __construct(string $environment, bool $debug, string $config = 'base')
    {
        parent::__construct($environment, $debug);

        $this->config = $config;
    }

    /**
     * {@inheritdoc}
     */
    public function registerBundles(): iterable
    {
        return [
            new FrameworkBundle(),
            new TwigBundle(),
            new LexikCronFileGeneratorBundle(),
            new Bundle(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getCacheDir(): string
    {
        return sys_get_temp_dir().'/LexikCronFileGeneratorBundle/cache';
    }

    /**
     * {@inheritdoc}
     */
    public function getLogDir(): string
    {
        return sys_get_temp_dir().'/LexikCronFileGeneratorBundle/logs';
    }

    protected function build(ContainerBuilder $container): void
    {
        $container->register('logger', NullLogger::class);
    }

    /**
     * Loads the container configuration.
     */
    public function registerContainerConfiguration(LoaderInterface $loader): void
    {
        $loader->load(sprintf(__DIR__.'/config/%s_config.yml', $this->config));
    }
}
