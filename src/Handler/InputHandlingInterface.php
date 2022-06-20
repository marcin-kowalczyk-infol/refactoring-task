<?php

namespace App\Handler;

interface InputHandlingInterface
{
    public function setInput(array $input = [], int $totalArguments = 0): void;

    public function isValidInput(): bool;
}
