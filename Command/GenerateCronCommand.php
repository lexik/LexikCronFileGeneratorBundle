<?php

namespace Lexik\Bundle\CronFileGeneratorBundle\Command;

use Lexik\Bundle\CronFileGeneratorBundle\Cron\DumpFileFactory;
use Lexik\Bundle\CronFileGeneratorBundle\Exception\CronEmptyException;
use Lexik\Bundle\CronFileGeneratorBundle\Exception\WrongConsoleBinPathException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class GenerateCronCommand extends Command
{
    private DumpFileFactory $dumpFileFactory;

    public function __construct(DumpFileFactory $dumpFileFactory, string $name = 'lexik:cron:generate-file')
    {
        parent::__construct($name);

        $this->dumpFileFactory = $dumpFileFactory;
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Generate a cron file')
            ->setHelp(<<<'EOPHP'
The <info>%command.name%</info> generates cron file

Crons are required to execute the command.
EOPHP
            )
            ->addOption('dry-run', null, InputOption::VALUE_NONE, 'Execute the dump file as a dry run.')
            ->addOption('check-path', 'c', InputOption::VALUE_NONE, 'Check absolute path on crontab generation.')
            ->addArgument('env-mode', InputArgument::REQUIRED, 'Env config')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->title('Generated cron file');

        $dryRun = (bool) $input->getOption('dry-run');
        $checkPath = (bool) $input->getOption('check-path');

        try {
            $dumpFile = $this->dumpFileFactory->createWithEnv($input->getArgument('env-mode'));
        } catch (CronEmptyException $e) {
            $output->writeln('<error>There is no cron in your configuration. Crons are required to execute this command.</error>');

            return 1;
        }

        if ($dryRun) {
            $result = $dumpFile->dryRun();

            $io->success('Dry run generated');

            $io->write($result);

            return 0;
        }

        if ($checkPath) {
            try {
                $dumpFile->checkPath();
            } catch (WrongConsoleBinPathException $e) {
                $output->writeln(sprintf('<error>%s.</error>', $e->getMessage()));

                return 1;
            }
        }

        $filename = $dumpFile->dumpFile();

        $io->success('File generated');

        $io->note(sprintf('path : %s', realpath($filename)));

        return 0;
    }
}
