<?php

namespace Lexik\Bundle\CronFileGeneratorBundle\Cron;

class Configuration
{
    private array $cronConfig;

    private array $crons = [];

    private string $env;

    private array $globalConfig;

    private array $availableEnvs;

    private string $readableAvailableEnvs;

    public function __construct(array $cronConfig)
    {
        $this->cronConfig = $cronConfig;
        $this->availableEnvs = $this->cronConfig['env_available'];
        $this->readableAvailableEnvs = implode(', ', $this->availableEnvs);

        $this->checkConfiguration();
    }

    public function initWithEnv(string $env): self
    {
        if (!\in_array($env, $this->availableEnvs)) {
            throw new \InvalidArgumentException('Env not available. Use this: '.$this->readableAvailableEnvs);
        }

        $this->env = $env;
        $this->init();
        $this->createCrons();

        return $this;
    }

    private function checkEnvConfiguration(array $searchIn, string $key): void
    {
        $availableEnvsCount = \count($this->availableEnvs);

        $countEnv = 0;
        foreach ($searchIn[$key] as $key => $env) {
            if (!\in_array($key, $this->availableEnvs)) {
                throw new \InvalidArgumentException('Env not available. Use this: '.$this->readableAvailableEnvs);
            }

            ++$countEnv;
        }

        if ($countEnv !== $availableEnvsCount) {
            throw new \InvalidArgumentException('You have missing env. Use this: '.$this->readableAvailableEnvs);
        }
    }

    public function getCrons(): array
    {
        return $this->crons;
    }

    public function getUser(): string
    {
        return $this->globalConfig['user'];
    }

    public function getAbsolutePath(): string
    {
        return $this->globalConfig['absolute_path'];
    }

    public function getPhpVersion(): string
    {
        return $this->globalConfig['php_version'];
    }

    public function getEnv(): string
    {
        return $this->globalConfig['env'];
    }

    public function getOutpath(): string
    {
        return $this->globalConfig['output_path'];
    }

    private function init(): void
    {
        $this->globalConfig = [
            'user' => $this->cronConfig['user'][$this->env],
            'absolute_path' => $this->cronConfig['absolute_path'][$this->env],
            'php_version' => $this->cronConfig['php_version'],
            'env' => $this->env,
            'output_path' => $this->cronConfig['output_path'],
        ];
    }

    private function createCrons(): self
    {
        $this->crons = [];

        foreach ($this->cronConfig['crons'] as $config) {
            $this->crons[] = new Cron($config['name'], $config['env'][$this->env], $config['command']);
        }

        return $this;
    }

    private function checkConfiguration(): void
    {
        foreach (['user', 'absolute_path'] as $value) {
            $this->checkEnvConfiguration($this->cronConfig, $value);
        }

        foreach ($this->cronConfig['crons'] as $cron) {
            $this->checkEnvConfiguration($cron, 'env');
        }
    }
}
