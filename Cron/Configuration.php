<?php

namespace Lexik\Bundle\CronFileGeneratorBundle\Cron;

class Configuration
{
    /**
     * @var array
     */
    private $cronConfig;

    /**
     * @var array
     */
    private $crons;

    /**
     * @var string
     */
    private $env;

    /**
     * @var array
     */
    private $globalConfig;

    /**
     * @var array
     */
    private $availablesEnvs;

    /**
     * @var string
     */
    private $readableEnvAvailables;

    public function __construct(array $cronConfig)
    {
        $this->cronConfig = $cronConfig;
        $this->availablesEnvs = $this->cronConfig['env_available'];
        $this->readableEnvAvailables = \implode(', ', $this->availablesEnvs);

        $this->checkConfiguration();
    }

    public function initWithEnv($env)
    {
        if (!\in_array($env, $this->availablesEnvs)) {
            throw new \InvalidArgumentException('Env not availables. Use this: '.$this->readableEnvAvailables);
        }

        $this->env = $env;
        $this->init();
        $this->createCrons();

        return $this;
    }

    private function checkEnvConfiguration(array $searchIn, string $key)
    {
        $countEnvAvailables = \count($this->availablesEnvs);

        $countEnv = 0;
        foreach ($searchIn[$key] as $key => $env) {
            if (!\in_array($key, $this->availablesEnvs)) {
                throw new \InvalidArgumentException('Env not availables. Use this: '.$this->readableEnvAvailables);
            }

            ++$countEnv;
        }

        if ($countEnv !== $countEnvAvailables) {
            throw new \InvalidArgumentException('You have missing env. Use this: '.$this->readableEnvAvailables);
        }
    }

    /**
     * @return array
     */
    public function getCrons()
    {
        return $this->crons;
    }

    public function getUser()
    {
        return $this->globalConfig['user'];
    }

    public function getAbsolutePath()
    {
        return $this->globalConfig['absolute_path'];
    }

    public function getPhpVersion()
    {
        return $this->globalConfig['php_version'];
    }

    public function getEnv()
    {
        return $this->globalConfig['env'];
    }

    public function getOutpath()
    {
        return $this->globalConfig['output_path'];
    }

    private function init()
    {
        $this->globalConfig = [
            'user' => $this->cronConfig['user'][$this->env],
            'absolute_path' => $this->cronConfig['absolute_path'][$this->env],
            'php_version' => $this->cronConfig['php_version'],
            'env' => $this->env,
            'output_path' => $this->cronConfig['output_path'],
        ];
    }

    private function createCrons()
    {
        $this->crons = [];

        foreach ($this->cronConfig['crons'] as $config) {
            $this->crons[] = new Cron($config['name'], $config['env'][$this->env], $config['command']);
        }

        return $this;
    }

    private function checkConfiguration()
    {
        $valuesToCheck = ['user', 'absolute_path'];

        foreach ($valuesToCheck as $value) {
            $this->checkEnvConfiguration($this->cronConfig, $value);
        }

        foreach ($this->cronConfig['crons'] as $cron) {
            $this->checkEnvConfiguration($cron, 'env');
        }
    }
}
