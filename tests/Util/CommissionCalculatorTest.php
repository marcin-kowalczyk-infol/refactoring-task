<?php

namespace Tests\Util;

use App\Util\CommissionCalculator;
use PHPUnit\Framework\TestCase;

class CommissionCalculatorTest extends TestCase
{
    /**
     * @dataProvider dataProvider
     */
    public function testCalculate(array $country, float $amount, float $expected)
    {
        $calculator = new CommissionCalculator();
        $result = $calculator->calculate($country, $amount);

        $this->assertEquals($expected, $result);
    }

    public function dataProvider(): array
    {
        return [
            [
                'country' => ['country' => ['alpha2' => 'PL']],
                'amount' => 1,
                'expected' => 0.01,
            ],
            [
                'country' => ['country' => ['alpha2' => 'ABC']],
                'amount' => 1,
                'expected' => 0.02,
            ],
            [
                'country' => ['country' => ['alpha2' => 'DE']],
                'amount' => 1.123456,
                'expected' => 0.02,
            ],
            [
                'country' => ['country' => ['alpha2' => 'ABC']],
                'amount' => 0,
                'expected' => 0,
            ],
        ];
    }
}
