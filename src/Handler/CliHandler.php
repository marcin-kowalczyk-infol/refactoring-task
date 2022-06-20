<?php

namespace App\Handler;

use App\Command\CalculateCommissionCommand;
use App\Command\RunnableInterface;

class CliHandler implements InputHandlingInterface
{
    private const COMMANDS_REGISTRY = [
        'calculate-commission' => CalculateCommissionCommand::class,
    ];

    public string $command;

    public array $argument = [];

    public function setInput(array $input = [], int $totalArguments = 0): void
    {
        $this->command = $input[1] ?? '';
        if ($totalArguments > 1) {
            for ($i = 2; $i < $totalArguments; $i++) {
                $this->argument[] = $input[$i];
            }
        }
    }

    public function isValidInput(): bool
    {
        return $this->isValidCommand($this->command);
    }

    public function getCommand(): RunnableInterface
    {
        if ($this->isValidCommand($this->command)) {
            $commandClass = self::COMMANDS_REGISTRY[$this->command];

            /** @var RunnableInterface $cliCommand */
            $cliCommand = new $commandClass();
            return $cliCommand;
        }

        throw new \RuntimeException('Invalid command name');
    }

    private function isValidCommand(string $command): bool
    {
        return \in_array($command, \array_keys(self::COMMANDS_REGISTRY));
    }
}
