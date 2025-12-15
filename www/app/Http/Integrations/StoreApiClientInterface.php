<?php

namespace App\Http\Integrations;

use App\Models\Tienda;
use Illuminate\Support\Collection;

interface StoreApiClientInterface
{
    public function searchProducts(Tienda $tienda, string $query): Collection;
    public function findProductById(Tienda $tienda, string $productId): ?array;
}