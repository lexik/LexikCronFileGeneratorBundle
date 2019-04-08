<?php

namespace Lexik\Bundle\CronFileGeneratorBundle\Cron;

use Lexik\Bundle\CronFileGeneratorBundle\Exception\CronEmptyException;

class DumpFileFactory
{
    /**
     * @var DumpFile
     */
    private $dumpFile;

    public function __construct(DumpFile $dumpFile)
    {
        $this->dumpFile = $dumpFile;
    }

    /**
     * @param string $env
     *
     * @return DumpFile
     *
     * @throws CronEmptyException
     */
    public function createWithEnv(string $env)
    {
        $this->dumpFile->init($env);

        return $this->dumpFile;
    }
}
