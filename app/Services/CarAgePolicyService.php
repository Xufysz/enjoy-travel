<?php

namespace App\Services;

class CarAgePolicyService
{
    public function getCarPriceFromAge(float $price, int $age): float
    {
        // Creates multipler based on age for young and senior
        $multiplier = 1;

        if ($age < 25) {
            $multiplier = 1.5;
        } elseif ($age > 65) {
            $multiplier = 1.2;
        }

        return $price * $multiplier;
    }
}
