<?php

namespace App\Repositories;

use App\Models\Tienda;
use Illuminate\Support\Collection;

class StoreRepository implements StoreRepositoryInterface
{
    public function getActiveStores(): Collection
    {
        return Tienda::where('EstadoTienda', true)
            ->whereIn('NombreTienda', ['Toys Store', 'Tech Store'])
            ->with('credenciales')
            ->get();
    }

    public function findByName(string $name): ?Tienda
    {
        return Tienda::where('NombreTienda', $name)
            ->where('EstadoTienda', true)
            ->with('credenciales')
            ->first();
    }

    public function getOtherStores(string $excludeStoreName): Collection
    {
        return Tienda::where('EstadoTienda', true)
            ->where('NombreTienda', '!=', $excludeStoreName)
            ->whereIn('NombreTienda', ['Toys Store', 'Tech Store'])
            ->with('credenciales')
            ->get();
    }
}