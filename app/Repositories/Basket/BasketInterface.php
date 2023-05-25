<?php

namespace App\Repositories\Basket;

use App\Models\Cart\DatabaseStorageModel;
use App\Models\Restaurant;

interface BasketInterface
{
    public function add(int $foodId,float $quantity, string $description, bool $update);

    public function all();

    public function remove(int $foodId);

    public function clear();

    public function totals();

    public function cartTotal();

    public function subTotal();

//    public function addAddress(Address $address): bool;
//
//    public function getAddress(): ?Address;
//
//    public function removeAddress(): bool;
//
    public function getRestaurant();

    public function addRestaurant(Restaurant $restaurant): bool;

    public function removeRestaurant(): bool;
}
