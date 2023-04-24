<?php

namespace Lexik\Bundle\CronFileGeneratorBundle\Cron;

class Cron
{
    private string $name;

    private string $expression;

    private string $command;

    public function __construct(string $name, string $expression, string $command)
    {
        $this->name = $name;
        $this->expression = $expression;
        $this->command = $command;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getExpression(): string
    {
        return $this->expression;
    }

    public function getCommand(): string
    {
        return $this->command;
    }
}
