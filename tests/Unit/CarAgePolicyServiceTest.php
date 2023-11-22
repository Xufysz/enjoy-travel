<?php

namespace Tests\Unit;

use App\Services\CarAgePolicyService;
use PHPUnit\Framework\TestCase;

class CarAgePolicyServiceTest extends TestCase
{

    /**
     * @var \App\Services\CarAgePolicyService
     */
    private CarAgePolicyService $carAgePolicyService;

    public function setup(): void
    {
        parent::setUp();

        $this->carAgePolicyService = app()[CarAgePolicyService::class];
    }

    // Test normal age
    public function test_get_car_price_from_age_normal()
    {
        $price = 10000;
        $age = 25;
        $expectedPrice = $this->carAgePolicyService->getCarPriceFromAge($price, $age);

        $this->assertEquals($price, $expectedPrice);
    }

    // Test young age
    public function test_get_car_price_from_age_young()
    {
        $price = 10000;
        $age = 17;
        $expectedPrice = $this->carAgePolicyService->getCarPriceFromAge($price, $age);

        $this->assertEquals($price * 1.5, $expectedPrice);
    }

    // Test senior age
    public function test_get_car_price_from_age_senior()
    {
        $price = 10000;
        $age = 66;
        $expectedPrice = $this->carAgePolicyService->getCarPriceFromAge($price, $age);

        $this->assertEquals($price * 1.2, $expectedPrice);
    }
}
