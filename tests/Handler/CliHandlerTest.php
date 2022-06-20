<?php

namespace Tests\Handler;

use App\Command\RunnableInterface;
use App\Handler\CliHandler;
use PHPUnit\Framework\TestCase;

class CliHandlerTest extends TestCase
{
    public function testGetCommandThrowsException()
    {
        $this->expectException(\RuntimeException::class);
        $cliHandler = new CliHandler();
        $cliHandler->setInput(['php', 'invalid-command', '3'], 3);
        $cliHandler->getCommand();
    }

    public function testGetCommandWhichIsRegistered()
    {
        $cliHandler = new CliHandler();
        $cliHandler->setInput(['php', 'calculate-commission', '3'], 3);
        $command = $cliHandler->getCommand();

        $this->assertEquals([RunnableInterface::class => RunnableInterface::class], \class_implements($command));
    }

    /**
     * @dataProvider dataProvider
     */
    public function testSetInputSetsCommand(array $input, int $arguments, $expectedCommand)
    {
        $cliHandler = new CliHandler();
        $cliHandler->setInput($input, $arguments);

        $this->assertEquals($cliHandler->command, $expectedCommand);
    }

    /**
     * @dataProvider dataProvider
     */
    public function testSetInputSetsArguments(array $input, int $arguments, $expectedArgument)
    {
        $cliHandler = new CliHandler();
        $cliHandler->setInput($input, $arguments);

        $this->assertEquals($cliHandler->command, $expectedArgument);
    }

    public function dataProvider(): array
    {
        return [
            [
                'input' => ['test', 'test2', 'test3'],
                'arguments' => 3,
                'expectedCommand' => 'test2',
                'expectedArgument' => ['test3'],
            ],
            [
                'input' => [],
                'arguments' => 0,
                'expectedCommand' => '',
                'expectedArgument' => [],
            ],
        ];
    }
}
