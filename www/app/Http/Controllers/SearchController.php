<?php

namespace App\Http\Controllers;

use App\Services\SearchService;
use App\Http\Requests\SearchRequest;
use Illuminate\View\View;

class SearchController
{
    public function __construct(
        private SearchService $searchService
    ) {}

    public function home(): View
    {
        return view('home');
    }

    public function search(SearchRequest $request): View
    {
        $query = $request->validated()['query'];
        
        try {
            $searchResult = $this->searchService->searchProducts($query);
            
            return view('search.results', [
                'query' => $query,
                'results' => $searchResult->productsByStore,
                'total_stores' => $searchResult->totalStores,
                'total_products' => $searchResult->totalProducts,
                'message' => $searchResult->getResultMessage()
            ]);
             
        } catch (\Exception $e) {
            return view('search.results', [
                'error' => 'Error en la búsqueda: ' . $e->getMessage(),
                'query' => $query
            ]);
        }
    }

    public function showProductDetail(string $storeName, string $productId): View
    {
        try {
            $productData = $this->searchService->getProductWithComparisons($storeName, $productId);
            
            return view('product.detail', [
                'baseProduct' => $productData['baseProduct'],
                'comparisons' => $productData['comparisons'],
                'storeName' => $storeName
            ]);

        } catch (\Exception $e) {
            \Log::error("Error al obtener detalle de producto", [
                'store' => $storeName, 
                'id' => $productId,
                'error' => $e->getMessage()
            ]);
            
            abort(404, 'Producto no encontrado');
        }
    }
}