<?php

namespace Lexik\Bundle\CronFileGeneratorBundle\Command;

use Lexik\Bundle\CronFileGeneratorBundle\Cron\DumpFileFactory;
use Lexik\Bundle\CronFileGeneratorBundle\Exception\CronEmptyException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class GenerateCronCommand extends Command
{
    protected static $defaultName = 'lexik:cron:generate-file';

    /**
     * @var DumpFileFactory
     */
    private $dumpFileFactory;

    public function __construct(DumpFileFactory $dumpFileFactory, $name = null)
    {
        parent::__construct($name);

        $this->dumpFileFactory = $dumpFileFactory;
    }

    protected function configure()
    {
        $description = <<<EOPHP
The <info>%command.name%</info> generate cron file

Crons are required for execute the command.
EOPHP;

        $this
            ->setName(static::$defaultName)
            ->setDescription($description)
            ->addOption('dry-run', null, InputOption::VALUE_NONE, 'Execute the dump file as a dry run.')
            ->addArgument('env-mode', InputArgument::REQUIRED, 'Env config')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $io->title('Generate cron file');

        $dryRun = (boolean) $input->getOption('dry-run');

        try {
            $dumpFile = $this->dumpFileFactory->createWithEnv($input->getArgument('env-mode'));
        } catch (CronEmptyException $e) {
            $output->writeln('<error>There is no crons in your configuration. Crons are required for execute the command.</error>');

            return 1;
        }

        if ($dryRun) {
            $result = $dumpFile->dryRun();

            $io->success('Dry run generated');

            $io->write($result);

            return 0;
        }

        $filename = $dumpFile->dumpFile();

        $io->success('File generated');

        $io->note(sprintf('path : %s', realpath($filename)));

        return 0;
    }
}
