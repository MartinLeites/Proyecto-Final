@extends('home')

@section('title', 'Resultados para "' . $query . '"')

@section('content')
<div class="search-results">
    <!-- Mensaje de error -->
    @if(isset($error))
        <div class="alert alert-error" role="alert" aria-live="assertive">
            <i class="fas fa-exclamation-triangle" aria-hidden="true"></i>
            <span>{{ $error }}</span>
        </div>
    @endif

    <!-- Header de resultados -->
    <div class="results-header">
        <h1 class="results-title">
            Resultados para <mark class="query">{{ e($query) }}</mark>
        </h1>
        <p class="results-subtitle">
            {{ $message ?? 'Buscando en nuestras tiendas...' }}
        </p>

        @php
            // Contador total de productos
            $total = 0;
            if (isset($results) && is_iterable($results)) {
                foreach ($results as $items) {
                    $total += is_countable($items) ? count($items) : 0;
                }
            }
        @endphp
        <p class="results-meta" aria-live="polite">
            <i class="fas fa-box-open" aria-hidden="true"></i>
            <strong>{{ number_format($total) }}</strong> producto(s) encontrado(s)
        </p>
    </div>

    <!-- Resultados MEZCLADOS - sin separación por tienda -->
    <div class="results-container">
    @if(isset($results) && count($results) > 0 && $total > 0)
        <section class="store-section centered-section" aria-labelledby="all-products">
            <ul class="products-grid centered-grid" role="list">
                @foreach($results as $storeName => $products)
                    @php $count = is_countable($products) ? count($products) : 0; @endphp
                    
                    @if($count > 0)
                        @foreach($products as $product)
                   
                                <article class="product">
                                    <div class="product-image">
                                        <img src="{{ $product->imagen_url }}" height="200px" width="200px" alt="{{ $product->nombre }}" 
                                             loading="lazy" decoding="async"
                                             onerror="this.src='{{ asset('imagenes/fallback.png') }}'; this.alt='Imagen no disponible';">
                                    </div>
                                    
                                    @if($product->en_oferta)
                                        <div class="sale-badge">OFERTA</div>
                                    @endif
                                    
                                    <div class="product-info">
                                        <h3 class="product-name">
                                            <a href="{{ route('product.detail', [
                                                'storeName' => $storeName,
                                                'productId' => $product->id 
                                            ]) }}" target="_blank" rel="noopener" class="product-link-title">
                                                {{ $product->nombre }}
                                            </a>
                                        </h3>
                                         <span class="category">{{ $product->categoria }}</span>
                                                                            
                                        <div class="price-section">
                                            @if($product->en_oferta)
                                                <span class="original-price">${{ number_format($product->precio_original, 2) }}</span>
                                                <span class="current-price">${{ number_format($product->precio_actual, 2) }}</span>
                                                <span class="savings">Ahorras ${{ number_format($product->ahorro, 2) }}!!!</span>
                                            @else
                                                <span class="current-price">${{ number_format($product->precio_actual, 2) }}</span>
                                            @endif
                                        </div>

                                        @php
                                            $rate = data_get($product, 'rating.rate', $product->valoracion ?? null);
                                            $countReviews = (int) data_get($product, 'rating.count', 0);
                                            $filled = max(0, min(5, (float) $rate));
                                            $empty = 5 - floor($filled);
                                        @endphp

                                        <div class="rating" aria-label="Valoración {{ $rate ?? 'N/A' }} sobre 5">
                                            <span class="stars" aria-hidden="true">
                                                @for ($i = 0; $i < floor($filled); $i++)
                                                    ★
                                                @endfor
                                                @for ($i = 0; $i < $empty; $i++)
                                                    ☆
                                                @endfor
                                            </span>
                                            <span class="rating-text">
                                                {{ $rate ?? 'N/A' }}
                                                @if($countReviews > 0)
                                                    <span class="review-count">({{ number_format($countReviews) }} reseñas)</span>
                                                @endif
                                            </span>
                                        </div>
                                        
                                        <div class="product-meta">
                                            @if($product->disponible)
                                                <span class="stock in-stock">Disponible</span>
                                            @else
                                                <span class="stock out-of-stock">Agotado</span>
                                            @endif
                                        </div>
                                    </div>
                                </article>
                        @endforeach
                    @endif
                @endforeach
            </ul>
        </section>
    @else
        <section class="no-results" aria-live="polite">
            <i class="fas fa-search" aria-hidden="true"></i>
            <h3>No se encontraron productos</h3>
            <p>Prueba con otros términos, por ejemplo: <code>phone</code>, <code>laptop</code>, <code>jacket</code>.</p>
        </section>
    @endif
</div>
@endsection