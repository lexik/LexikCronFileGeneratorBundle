<?php

namespace Lexik\Bundle\CronFileGeneratorBundle\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Filesystem\Filesystem;

abstract class TestCase extends WebTestCase
{
    protected static $client;

    /**
     * {@inheritdoc}
     */
    protected static function createKernel(array $options = [])
    {
        require_once __DIR__.'/app/AppKernel.php';

        return new AppKernel('test', true, isset($options['config']) ? $options['config'] : 'base');
    }

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $fs = new Filesystem();
        $fs->remove(\sys_get_temp_dir().'/LexikCronFileGeneratorBundle/');
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        static::$kernel = null;
    }
}
