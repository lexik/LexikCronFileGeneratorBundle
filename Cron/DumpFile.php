<?php

namespace Lexik\Bundle\CronFileGeneratorBundle\Cron;

use Lexik\Bundle\CronFileGeneratorBundle\Exception\CronEmptyException;
use Lexik\Bundle\CronFileGeneratorBundle\Exception\WrongConsoleBinPathException;
use Symfony\Component\Filesystem\Filesystem;
use Twig\Environment;

class DumpFile
{
    private Environment $twigEngine;

    private Configuration $configuration;

    private string $env;

    public function __construct(
        Environment $twigEngine,
        Configuration $configuration
    ) {
        $this->twigEngine = $twigEngine;
        $this->configuration = $configuration;
    }

    public function dumpFile(): string
    {
        $destination = $this->configuration->getOutpath();

        $filesystem = new Filesystem();
        $filesystem->dumpFile($destination, $this->render());

        return $destination;
    }

    public function dryRun(): string
    {
        return $this->render();
    }

    public function init($env): void
    {
        $this->configuration->initWithEnv($env);
        $this->env = $env;

        if ($this->configuration->getCheckAbsolutePath()
            && false === file_exists($binPath = $this->configuration->getAbsolutePath())
        ) {
            throw new WrongConsoleBinPathException(sprintf('The configured "absolute_path: %s" does not exist', $binPath));
        }

        if (empty($this->configuration->getCrons())) {
            throw new CronEmptyException('There is no cron found in the configuration');
        }
    }

    private function render(): string
    {
        return $this->twigEngine->render('@LexikCronFileGenerator/template.txt.twig', [
            'crons' => $this->configuration->getCrons(),
            'user' => $this->configuration->getUser(),
            'absolute_path' => $this->configuration->getAbsolutePath(),
            'php_version' => $this->configuration->getPhpVersion(),
            'env' => $this->env,
            'mailto' => $this->configuration->getMailto(),
        ]);
    }
}
