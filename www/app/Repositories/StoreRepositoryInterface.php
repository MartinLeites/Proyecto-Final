<?php

namespace App\Repositories;

use App\Models\Tienda;
use Illuminate\Support\Collection;

interface StoreRepositoryInterface
{
    public function getActiveStores(): Collection;
    public function findByName(string $name): ?Tienda;
    public function getOtherStores(string $excludeStoreName): Collection;
}