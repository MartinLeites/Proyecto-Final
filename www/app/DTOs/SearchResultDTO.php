<?php

namespace App\DTOs;

use Illuminate\Support\Collection;

class SearchResultDTO
{
    public function __construct(
        public string $query,
        public Collection $productsByStore, // [ 'Tienda A' => [ProductDTO, ProductDTO] ]
        public int $totalProducts,
        public int $totalStores
    ) {}

    public function getResultMessage(): string
    {
        if ($this->totalProducts === 0) {
            return "No se encontraron resultados para \"{$this->query}\"";
        }
        return "Se encontraron {$this->totalProducts} productos en {$this->totalStores} tiendas para \"{$this->query}\"";
    }
}