<?php

namespace Lexik\Bundle\CronFileGeneratorBundle\Tests\Functional\Command;

use Lexik\Bundle\CronFileGeneratorBundle\Tests\Functional\TestCase;
use Symfony\Component\Console\Tester\CommandTester;

class GenerateCronFileCommandTest extends TestCase
{
    public function testGenerateFullConfiguration()
    {
        $kernel = $this->bootKernel();

        $tester = new CommandTester(
            $kernel->getContainer()->get('lexik_bundle_cron_file_generator.command.generate_cron_command')
        );

        $this->assertSame(0, $tester->execute(['env-mode' => 'staging']));

        $expected = '* * * * * project_staging php7.3 path/to/staging app:test --env=staging';

        $cacheDir = $kernel->getContainer()->getParameter('kernel.cache_dir') . '/cron_test';

        $this->assertStringContainsString($expected, \file_get_contents($cacheDir));
    }

    public function testGenerateEmptyCrons()
    {
        $kernel = $this->bootKernel(['config' => 'empty_cron']);

        $tester = new CommandTester(
            $kernel->getContainer()->get('lexik_bundle_cron_file_generator.command.generate_cron_command')
        );

        $this->assertSame(1, $tester->execute(['env-mode' => 'staging']));

        $expected = 'There is no cron in your configuration. Crons are required to execute this command';

        $this->assertStringContainsString($expected, $tester->getDisplay());
    }

    public function testDryRun()
    {
        $kernel = $this->bootKernel();

        $tester = new CommandTester(
            $kernel->getContainer()->get('lexik_bundle_cron_file_generator.command.generate_cron_command')
        );

        $this->assertSame(0, $tester->execute(['env-mode' => 'staging', '--dry-run' => true]));

        $this->assertStringContainsString('[OK] Dry run generated', $tester->getDisplay());
        $this->assertStringContainsString('# send email', $tester->getDisplay());
        $this->assertStringContainsString(
            '* * * * * project_staging php7.3 path/to/staging app:test --env=staging',
            $tester->getDisplay()
        );
    }
}
