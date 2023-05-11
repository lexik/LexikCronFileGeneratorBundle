<?php

namespace Lexik\Bundle\CronFileGeneratorBundle\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\KernelInterface;

abstract class TestCase extends WebTestCase
{
    use ForwardCompatTestCaseTrait;

    protected static $client;

    /**
     * {@inheritdoc}
     */
    protected static function createKernel(array $options = []): KernelInterface
    {
        require_once __DIR__.'/app/AppKernel.php';

        return new AppKernel('test', true, isset($options['config']) ? $options['config'] : 'base');
    }

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        $fs = new Filesystem();
        $fs->remove(sys_get_temp_dir().'/LexikCronFileGeneratorBundle/');
    }
}
