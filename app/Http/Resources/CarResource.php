<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Services\CarAgePolicyService;

class CarResource extends JsonResource
{
    /**
     * @var \App\Services\CarAgePolicyService
     */
    private CarAgePolicyService $carAgePolicyService;

    // Constructor
    public function __construct($resource)
    {
        $this->carAgePolicyService = app()[CarAgePolicyService::class];
        parent::__construct($resource);
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Gets inflated price by passing car price and age from request. Defaults to 18 if no age is passed.
        // Assumption made that we always want to inflate the price given requirements, but
        // this could also be abstracted up into a CarHireService class that would handle
        // the logic of whether to inflate the price or not.

        $ageInflatedPrice = $this->carAgePolicyService->getCarPriceFromAge($this->price, $request->query('age', 18));
        return array_merge(parent::toArray($request), [
            'price' => $ageInflatedPrice,
        ]);
    }
}
