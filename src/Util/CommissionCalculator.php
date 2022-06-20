<?php

namespace App\Util;

class CommissionCalculator
{
    private const EU_COUNTRY_CODES = [
        'AT' => 'Austria',
        'BE' => 'Belgium',
        'BG' => 'Bulgaria',
        'HR' => 'Croatia',
        'CY' => 'Cyprus',
        'CZ' => 'Czech Republic',
        'DK' => 'Denmark',
        'EE' => 'Estonia',
        'FI' => 'Finland',
        'FR' => 'France',
        'DE' => 'Germany',
        'GR' => 'Greece',
        'HU' => 'Hungary',
        'IE' => 'Ireland',
        'IT' => 'Italy',
        'LV' => 'Latvia',
        'LT' => 'Lithuania',
        'LU' => 'Luxembourg',
        'MT' => 'Malta',
        'NL' => 'Netherlands',
        'PL' => 'Poland',
        'PT' => 'Portugal',
        'RO' => 'Romania',
        'SK' => 'Slovakia (Slovak Republic)',
        'SI' => 'Slovenia',
        'ES' => 'Spain',
        'SE' => 'Sweden',
    ];
    private const COMMISSION_RATE_EU = 0.01;
    private const COMMISSION_RATE_NON_EU = 0.02;

    public function calculate(array $country, float $amount): string
    {
        if (!isset($country['country']['alpha2'])) {
            throw new \RuntimeException('Invalid bin or lookup problem');
        }

        $commissionRate = $this->isEuCountry($country['country']['alpha2']) ?
            self::COMMISSION_RATE_EU : self::COMMISSION_RATE_NON_EU;

        return \ceil($amount * $commissionRate * 100) / 100;
    }

    private function isEuCountry(string $alpha2): bool
    {
        return \in_array($alpha2, \array_keys(self::EU_COUNTRY_CODES));
    }
}
