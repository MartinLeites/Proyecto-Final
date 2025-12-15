<?php

namespace App\Services;

use App\Repositories\StoreRepositoryInterface;
use App\Http\Integrations\StoreApiClientInterface;
use App\DTOs\ProductDTO;
use App\DTOs\SearchResultDTO;
use Illuminate\Support\Collection;

class SearchService
{
    public function __construct(
        private StoreRepositoryInterface $storeRepository,
        private StoreApiClientInterface $apiClient
    ) {}

    public function searchProducts(string $query): SearchResultDTO
    {
        $activeStores = $this->storeRepository->getActiveStores();
        $allProducts = $this->searchInAllStores($activeStores, $query);
        $cheapestProducts = $this->filterCheapestProducts($allProducts);
        
        return new SearchResultDTO(
            query: $query,
            productsByStore: $cheapestProducts,
            totalProducts: $this->countTotalProducts($cheapestProducts),
            totalStores: $cheapestProducts->count()
        );
    }

    public function getProductWithComparisons(string $storeName, string $productId): array
    {
        $baseStore = $this->storeRepository->findByName($storeName);
        
        if (!$baseStore) {
            throw new \Exception("Tienda no encontrada: {$storeName}");
        }

        $baseProductData = $this->apiClient->findProductById($baseStore, $productId);
        
        if (!$baseProductData) {
            throw new \Exception("Producto no encontrado en la tienda base");
        }

        $baseProduct = ProductDTO::fromApiResponse($baseProductData, $storeName);
        
        $otherStores = $this->storeRepository->getOtherStores($storeName);
        $comparisons = $this->findProductInOtherStores($productId, $otherStores);

        return [
            'baseProduct' => $baseProduct,
            'comparisons' => $comparisons
        ];
    }

    private function searchInAllStores(Collection $stores, string $query): Collection
    {
        $results = collect();
        
        foreach ($stores as $store) {
            $apiProducts = $this->apiClient->searchProducts($store, $query);
            
            $transformedProducts = $apiProducts->map(function ($product) use ($store) {
                return ProductDTO::fromApiResponse($product, $store->NombreTienda);
            });
            
            if ($transformedProducts->isNotEmpty()) {
                $results->put($store->NombreTienda, $transformedProducts);
            }
        }
        
        return $results;
    }

    private function filterCheapestProducts(Collection $storeProducts): Collection
    {
        $cheapestProducts = collect();
        
        foreach ($storeProducts as $storeName => $products) {
            foreach ($products as $product) {
                $productKey = $product->id;
                $productPrice = $product->precio_actual;
                
                if (!$cheapestProducts->has($productKey) || 
                    $productPrice < $cheapestProducts[$productKey]->precio_actual) {
                    $cheapestProducts[$productKey] = $product;
                }
            }
        }
        
        // Reagrupar por tienda
        return $cheapestProducts->groupBy('tienda_nombre');
    }

    private function findProductInOtherStores(string $productId, Collection $otherStores): Collection
    {
        $comparisons = collect();
        
        foreach ($otherStores as $store) {
            $productData = $this->apiClient->findProductById($store, $productId);
            
            if ($productData) {
                $product = ProductDTO::fromApiResponse($productData, $store->NombreTienda);
                $comparisons->put($store->NombreTienda, $product);
            }
        }
        
        return $comparisons;
    }

    private function countTotalProducts(Collection $productsByStore): int
    {
        return $productsByStore->flatten()->count();
    }
}