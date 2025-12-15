@extends('layouts.barra')

@push('styles')
    @vite('resources/css/resultados.css')
@endpush

@section('title', $baseProduct->nombre)

@section('content')

    <div class="producto">

        <img src="{{ $baseProduct->imagen_url }}" alt="{{ $baseProduct->nombre }}" class="imagen-Producto">

        <div class="info-Producto">
            <h1 id="nombreProducto">{{ $baseProduct->nombre }}</h1>

            <p>Visto originalmente en: <strong>{{ $storeName }}</strong></p>

            <h2>Precio Actual</h2>

            <p>${{ number_format($baseProduct->precio_actual, 2) }}</p>

            {{-- Mostrar información de oferta si aplica --}}
            @if ($baseProduct->en_oferta)
                <p>Precio Original: ${{ number_format($baseProduct->precio_original, 2) }}</p>
                <span>¡Ahorras ${{ number_format($baseProduct->ahorro, 2) }}!</span>
            @endif

            <p>{{ $baseProduct->descripcion ?? 'Sin descripción disponible.' }}</p>

            {{-- Botón de compra para la tienda actual --}}
            <a href="{{ $baseProduct->enlace_producto }}" target="_blank" rel="noopener">Comprar en {{ $storeName }}</a>

            <div class="categoria-Producto">
                <span>Categoría: {{ $baseProduct->categoria ?? 'N/A' }}</span>
                <span class="{{ $baseProduct->disponible ? 'in-stock' : 'out-of-stock' }}">
                    {{ $baseProduct->disponible ? 'Disponible' : 'Agotado' }}
                </span>
            </div>

            <div class="seccion_comparacion">

                <h2>Comparación de Precios en Otras Tiendas</h2>

                @if (count($comparisons) > 0)
                    <table class="tabla_tiendas_producto">
                        <thead>
                            <tr>
                                <th>Tienda</th>
                                <th>Precio</th>
                                <th>Estado</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- Fila para la tienda original (el producto en el que hicieron clic) --}}
                            <tr>
                                <td><strong>{{ $storeName }} (Original)</strong></td>
                                <td data-label="Precio">${{ number_format($baseProduct->precio_actual, 2) }}</td>
                                <td data-label="Estado">Disponible</td>
                                <td data-label="Acción">
                                    <a href="{{ $baseProduct->enlace_producto }}" target="_blank" rel="noopener"
                                        class="btn btn-sm btn-success">Ver</a>
                                </td>
                            </tr>

                            {{-- Iterar sobre los productos encontrados en otras tiendas --}}
                            @foreach ($comparisons as $store => $product)
                                <tr class="{{ $product->precio_actual < $baseProduct->precio_actual ? 'cheaper' : '' }}">
                                    <td>{{ $store }}</td>
                                    <td data-label="Precio">
                                        ${{ number_format($product->precio_actual, 2) }}
                                        {{-- Resaltar si esta opción es más barata que el producto base --}}
                                        @if ($product->precio_actual < $baseProduct->precio_actual)
                                            <span class="badge badge-cheapest">¡Más Barato!</span>
                                        @endif
                                    </td>
                                    <td data-label="Estado"
                                        class="{{ $product->disponible ? 'text-success' : 'text-danger' }}">
                                        {{ $product->disponible ? 'Disponible' : 'Agotado' }}
                                    </td>
                                    <td data-label="Acción">
                                        {{-- El botón solo es funcional si está disponible --}}
                                        <a href="{{ $product->enlace_producto }}" target="_blank" rel="noopener"
                                            class="btn btn-sm btn-primary" {{ $product->disponible ? '' : 'disabled' }}>Ver
                                            Oferta</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="alert alert-warning">No se encontraron precios similares en otras tiendas.</p>
                @endif
            </div>
        </div>
    </div>

    {{-- Enlace para volver a la página anterior --}}
    <a href="{{ url()->previous() }}" class="back-link">&larr; Volver a los resultados</a>

    </div>
@endsection
