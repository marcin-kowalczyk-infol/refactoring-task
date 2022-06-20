<?php

namespace App\Command;

use App\Util\BinlistLookup;
use App\Util\CommissionCalculator;
use App\Util\ExchangeRateLookup;
use App\Util\SourceFileDataNormalizer;

class CalculateCommissionCommand implements RunnableInterface
{
    public const DEFAULT_CURRENCY = 'EUR';

    private SourceFileDataNormalizer $dataNormalizer;

    private CommissionCalculator $commissionCalculator;

    public BinlistLookup $binlistLookup;

    private ExchangeRateLookup $exchangeRateLookup;

    public function __construct()
    {
        $this->setDependencies();
    }

    public function setDependencies(
        SourceFileDataNormalizer $normalizer = null,
        CommissionCalculator $commissionCalculator = null,
        BinlistLookup $binlistLookup = null,
        ExchangeRateLookup $exchangeRateLookup = null,
    ) {
        $this->dataNormalizer = $normalizer !== null ?: new SourceFileDataNormalizer();
        $this->commissionCalculator = $commissionCalculator !== null ?: new CommissionCalculator();
        $this->binlistLookup = $binlistLookup ?: new BinlistLookup();
        $this->exchangeRateLookup = $exchangeRateLookup ?: new ExchangeRateLookup();
    }

    public function run(array $arguments): \Generator
    {
        $filename = $arguments[0] ?? '';
        if (!\file_exists($filename)) {
            throw new \RuntimeException("Invalid filename or file doesn't exist");
        }

        $fileHandle = \fopen($filename, "r") or throw new \RuntimeException("Couldn't open file for read");
        return $this->processFile($fileHandle);
    }

    private function processFile($fileHandle): \Generator
    {
        if (!\is_resource($fileHandle)) {
            throw new \RuntimeException("Invalid resource");
        }

        try {
            while (!\feof($fileHandle)) {
                yield $this->processLine(\fgets($fileHandle, 100));
            }
        } finally {
            \fclose($fileHandle);
        }
    }

    private function processLine(string $line): string
    {
        if ($line === '') {
            return '';
        }

        $lineData = $this->dataNormalizer->normalizeLine($line);
        $country = $this->binlistLookup->fetchCardInformation($lineData['bin']);

        $amountInDefaultCurrency = $lineData['amount'];
        if ($lineData['currency'] !== self::DEFAULT_CURRENCY) {
            $exchangeRate = $this->exchangeRateLookup->fetchRate(
                $lineData['currency'],
                self::DEFAULT_CURRENCY,
                $lineData['amount'],
            );

            $amountInDefaultCurrency = $exchangeRate['result'] ?? 0;
        }

        try {
            return $this->commissionCalculator->calculate($country, $amountInDefaultCurrency);
        } catch (\RuntimeException $e) {
            die("Problem occurred, invalid data provided: {$e->getMessage()}");
        }
    }
}
