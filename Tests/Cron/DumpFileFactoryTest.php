<?php

namespace Lexik\Bundle\CronFileGeneratorBundle\Tests\Cron;

use Lexik\Bundle\CronFileGeneratorBundle\Cron\DumpFile;
use Lexik\Bundle\CronFileGeneratorBundle\Cron\DumpFileFactory;
use PHPUnit\Framework\TestCase;

class DumpFileFactoryTest extends TestCase
{
    public function testFactory()
    {
        $dumpFile = $this->createMock(DumpFile::class);
        $dumpFile->expects($this->once())->method('init');

        $factory = new DumpFileFactory($dumpFile);

        $this->assertSame($dumpFile, $factory->createWithEnv('env'));
    }
}
