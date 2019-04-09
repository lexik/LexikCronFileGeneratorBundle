<?php

namespace Lexik\Bundle\CronFileGeneratorBundle\Cron;

use Lexik\Bundle\CronFileGeneratorBundle\Exception\CronEmptyException;
use Symfony\Component\Filesystem\Filesystem;
use Twig\Environment;

class DumpFile
{
    /**
     * @var Environment
     */
    private $twigEngine;

    /**
     * @var Configuration
     */
    private $configuration;

    /**
     * @var string
     */
    private $env;

    public function __construct(
        Environment $twigEngine,
        Configuration $configuration
    ) {
        $this->twigEngine = $twigEngine;
        $this->configuration = $configuration;
    }

    public function dumpFile()
    {
        $destination = $this->configuration->getOutpath();

        $filesystem = new Filesystem();
        $filesystem->dumpFile($destination, $this->render());

        return $destination;
    }

    public function dryRun()
    {
        return $this->render();
    }

    public function init($env)
    {
        $this->configuration->initWithEnv($env);
        $this->env = $env;

        if (empty($this->configuration->getCrons())) {
            throw new CronEmptyException('There is no crons found in the configuration.');
        }
    }

    private function render()
    {
        return $this->twigEngine->render('template.txt.twig', [
            'crons' => $this->configuration->getCrons(),
            'user' => $this->configuration->getUser(),
            'absolute_path' => $this->configuration->getAbsolutePath(),
            'php_version' => $this->configuration->getPhpVersion(),
            'env' => $this->env,
        ]);
    }
}
