<?php

namespace Lexik\Bundle\CronFileGeneratorBundle\Tests\Stubs;

use Lexik\Bundle\CronFileGeneratorBundle\Cron\DumpFile;

class Autowired
{
    /**
     * @var DumpFile
     */
    private $dumpFile;

    public function __construct(DumpFile $dumpFile)
    {
        $this->dumpFile = $dumpFile;
    }
}
