<?php

namespace App\DTOs;

class ProductDTO
{
    public function __construct(
        public string $id,
        public string $nombre,
        public float $precio_actual,
        public ?float $precio_original,
        public string $categoria,
        public string $enlace_producto,
        public string $imagen_url,
        public bool $disponible,
        public ?float $valoracion,
        public string $tienda_nombre,
        public bool $en_oferta,
        public float $ahorro
    ) {}

    
    public static function fromApiResponse(array $data, string $tiendaNombre): self
    {
        $precioOriginal = $data['precio_original'] ?? $data['precio_actual'];
        $enOferta = isset($data['precio_original']) && $data['precio_actual'] < $data['precio_original'];
        $ahorro = $enOferta ? $precioOriginal - $data['precio_actual'] : 0;

        return new self(
            id: (string) $data['id'],
            nombre: $data['nombre'],
            precio_actual: (float) $data['precio_actual'],
            precio_original: $precioOriginal !== $data['precio_actual'] ? (float) $precioOriginal : null,
            categoria: $data['categoria'] ?? '',
            enlace_producto: $data['enlace_producto'] ?? '#',
            imagen_url: $data['imagen_url'] ?? '',
            disponible: $data['disponible'] ?? true,
            valoracion: isset($data['valoracion']) ? (float) $data['valoracion'] : null,
            tienda_nombre: $tiendaNombre,
            en_oferta: $enOferta,
            ahorro: $ahorro
        );
    }
}