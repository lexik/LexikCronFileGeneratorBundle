<?php

namespace Lexik\Bundle\CronFileGeneratorBundle\Tests\Stubs;

use Lexik\Bundle\CronFileGeneratorBundle\Cron\DumpFile;

class Autowired
{
    private DumpFile $dumpFile;

    public function __construct(DumpFile $dumpFile)
    {
        $this->dumpFile = $dumpFile;
    }

    public function getDumpFile(): DumpFile
    {
        return $this->dumpFile;
    }
}
