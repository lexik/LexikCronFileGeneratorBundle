<?php

namespace Lexik\Bundle\CronFileGeneratorBundle\Cron;

class Cron
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $expression;

    /**
     * @var string
     */
    private $command;

    public function __construct(string $name, string $expression, string $command)
    {
        $this->name = $name;
        $this->expression = $expression;
        $this->command = $command;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getExpression()
    {
        return $this->expression;
    }

    /**
     * @return string
     */
    public function getCommand()
    {
        return $this->command;
    }
}
