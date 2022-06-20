<?php

namespace Tests\Command;

use App\Command\CalculateCommissionCommand;
use App\Util\BinlistLookup;
use App\Util\ExchangeRateLookup;
use PHPUnit\Framework\TestCase;

class CalculateCommissionCommandTest extends TestCase
{
    public function testThrowsExceptionForMissingFilename()
    {
        $this->expectException(\RuntimeException::class);

        $calculator = new CalculateCommissionCommand();
        $calculator->run([]);
    }

    public function testValidFilename()
    {
        $filename = __DIR__ . '/../Fixtures/testInputFile.txt';
        $calculatorMock = new CalculateCommissionCommand();
        $generator = $calculatorMock->run([$filename]);

        $this->assertIsIterable($generator);
    }

    /**
     * @dataProvider dataProvider
     * @group run
     */
    public function testRun(string $filename, array $country, array $rate, string $expected)
    {
        $binlistLookupMock = $this->getMockBuilder(BinlistLookup::class)->getMock();
        $binlistLookupMock
            ->expects($this->atLeastOnce())
            ->method('fetchCardInformation')
            ->willReturn($country);

        $exchangeRateLookupMock = $this->getMockBuilder(ExchangeRateLookup::class)->getMock();
        $exchangeRateLookupMock
            ->expects($this->atLeastOnce())
            ->method('fetchRate')
            ->willReturn($rate);

        $command = new CalculateCommissionCommand();
        $command->setDependencies(
            binlistLookup: $binlistLookupMock,
            exchangeRateLookup: $exchangeRateLookupMock,
        );

        $commissions = $command->run([$filename]);

        $output = '';
        foreach ($commissions as $commission) {
            if ($commission != '') {
                $output .= $commission . \PHP_EOL;
            }
        }

        $this->assertEquals($output, $expected);
    }

    public function dataProvider(): array
    {
        return [
            [
                'filename' => __DIR__ . '/../Fixtures/testInputFile.txt',
                'country' => ['country' => ['alpha2' => 'DK']],
                'rate' => ['result' => 100],
                'expected' => '1' . \PHP_EOL . '1' . \PHP_EOL,
            ],
            [
                'filename' => __DIR__ . '/../Fixtures/testInputFile.txt',
                'country' => ['country' => ['alpha2' => 'ABC']],
                'rate' => ['result' => 100],
                'expected' => '2' . \PHP_EOL . '2' . \PHP_EOL,
            ],
            [
                'filename' => __DIR__ . '/../Fixtures/testInputFile.txt',
                'country' => ['country' => ['alpha2' => 'ABC']],
                'rate' => ['result' => 3.33],
                'expected' => '2' . \PHP_EOL . '0.07' . \PHP_EOL,
            ],
        ];
    }
}
