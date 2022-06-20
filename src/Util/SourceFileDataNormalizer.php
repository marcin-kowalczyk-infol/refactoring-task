<?php

namespace App\Util;

class SourceFileDataNormalizer
{
    /**
     * @return array{bin: string, amount: float, currency: string}
     */
    public function normalizeLine(string $line): array
    {
        $data = \json_decode($line);
        return [
            //not sure if bin is always int, hence I cast it to string
            'bin' => (string) $data?->bin,
            'amount' => (float) $data?->amount,
            'currency' => (string) $data?->currency,
        ];
    }
}
