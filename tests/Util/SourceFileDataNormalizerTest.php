<?php

namespace Tests\Util;

use App\Util\SourceFileDataNormalizer;
use PHPUnit\Framework\TestCase;

class SourceFileDataNormalizerTest extends TestCase
{
    /**
     * @dataProvider dataProvider
     */
    public function testNormalizeLine(string $line, array $expected)
    {
        $normalizer = new SourceFileDataNormalizer();
        $normalized = $normalizer->normalizeLine($line);

        $this->assertEquals($expected, $normalized);
    }

    public function dataProvider(): array
    {
        return [
            [
                'line' => '',
                'expected' => [
                    'bin' => '',
                    'amount' => 0,
                    'currency' => '',
                ],
            ],
            [
                'line' => '{"bin":"516793","amount":"50.00","currency":"USD"}',
                'expected' => [
                    'bin' => '516793',
                    'amount' => 50.00,
                    'currency' => 'USD',
                ],
            ],
        ];
    }
}
