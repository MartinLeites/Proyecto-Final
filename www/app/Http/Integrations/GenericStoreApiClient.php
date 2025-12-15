<?php

namespace App\Http\Integrations;

use App\Models\Tienda;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GenericStoreApiClient implements StoreApiClientInterface
{
    public function searchProducts(Tienda $tienda, string $query): Collection
    {
        try {
            $headers = $this->prepareHeaders($tienda);
            
            $response = Http::withHeaders($headers)
                ->timeout(15)
                ->retry(3, 100)
                ->get("{$tienda->URLTienda}/products", [
                    'search' => $query,
                    'disponible' => true
                ]);

            if ($response->successful() && $this->isValidApiResponse($response->json())) {
                return collect($response->json()['data'] ?? []);
            }

            Log::warning("API {$tienda->NombreTienda} respondió con error", [
                'status' => $response->status()
            ]);

        } catch (\Exception $e) {
            Log::error("Error en tienda {$tienda->NombreTienda}", [
                'error' => $e->getMessage()
            ]);
        }

        return collect();
    }

    public function findProductById(Tienda $tienda, string $productId): ?array
    {
        try {
            $headers = $this->prepareHeaders($tienda);
            
            $response = Http::withHeaders($headers)
                ->timeout(10)
                ->get("{$tienda->URLTienda}/products", [
                    'universal_id' => $productId
                ]);

            if ($response->successful() && $this->isValidApiResponse($response->json())) {
                return $response->json()['data'][0] ?? null;
            }

        } catch (\Exception $e) {
            Log::error("Error buscando producto en {$tienda->NombreTienda}", [
                'error' => $e->getMessage()
            ]);
        }

        return null;
    }

    private function prepareHeaders(Tienda $tienda): array
    {
        $headers = [
            'Content-Type' => 'application/json',
            'User-Agent' => 'MiComparadorPrecios/1.0'
        ];

        $token = $tienda->getToken();
        if ($token && $token !== 'no_requerido') {
            $headers['Authorization'] = "Bearer {$token}";
        }

        return $headers;
    }

    private function isValidApiResponse(?array $apiData): bool
    {
        return $apiData && isset($apiData['success']) && $apiData['success'] === true;
    }
}