<?php

namespace Tests\Feature;

use App\Models\Car;
use App\Services\CarAgePolicyService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CarsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var \App\Services\CarAgePolicyService
     */
    private CarAgePolicyService $carAgePolicyService;

    public function setup(): void
    {
        parent::setUp();

        // Allows us to mock this if we wanted to
        $this->carAgePolicyService = app()[CarAgePolicyService::class];
    }

    // Test to successfully get all cars
    public function test_get_all_cars()
    {
        $response = $this->get('/api/cars');

        $response->assertStatus(200);
    }

    // Test to get cars with young age and check price
    public function test_get_cars_young_age()
    {
        $price = 10000;
        $car = Car::factory()->create([
            'price' => $price,
        ]);

        $age = 18;
        $expectedPrice = $this->carAgePolicyService->getCarPriceFromAge($price, $age);

        $response = $this->get('/api/cars?age=' . $age);

        $response
            ->assertStatus(200)
            ->assertJsonFragment([
                'id' => $car->id,
                'price' => $expectedPrice,
            ]);
    }


    // Test to get cars with senior age and check price
    public function test_get_cars_senior_age()
    {
        $price = 10000;
        $car = Car::factory()->create([
            'price' => $price,
        ]);

        $age = 85;
        $expectedPrice = $this->carAgePolicyService->getCarPriceFromAge($price, $age);

        $response = $this->get('/api/cars?age=' . $age);

        $response
            ->assertStatus(200)
            ->assertJsonFragment([
                'id' => $car->id,
                'price' => $expectedPrice,
            ]);
    }

    // Test update car
    public function test_update_car()
    {
        $car = Car::factory()->create();
        $expectedCar = [
            'make' => 'Ford',
            'model' => 'Fiesta',
            'year' => 2010,
            'color' => 'red',
            'price' => 10000,
        ];

        $response = $this->put(route('cars.update', $car->id), $expectedCar);

        $response->assertStatus(200);
        $this->assertDatabaseHas('cars', $expectedCar);
    }

    // Test delete car
    public function test_delete_car()
    {
        $car = Car::factory()->create();

        $response = $this->delete(route('cars.destroy', $car->id));

        $response->assertStatus(204);
        $this->assertDatabaseMissing('cars', $car->toArray());
    }
}
