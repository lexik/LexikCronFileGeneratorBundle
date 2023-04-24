<?php

namespace Lexik\Bundle\CronFileGeneratorBundle\Cron;

use Lexik\Bundle\CronFileGeneratorBundle\Exception\CronEmptyException;

class DumpFileFactory
{
    private DumpFile $dumpFile;

    public function __construct(DumpFile $dumpFile)
    {
        $this->dumpFile = $dumpFile;
    }

    /**
     * @throws CronEmptyException
     */
    public function createWithEnv(string $env): DumpFile
    {
        $this->dumpFile->init($env);

        return $this->dumpFile;
    }
}
