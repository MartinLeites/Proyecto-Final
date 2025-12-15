@extends('layouts.menu_principal')

@section('title', 'Comparador de precios – ShockingSales')

@section('content')
@yield('search-content')

    @guest('admin')

        {{-- Cómo funciona (3 pasos) --}}
        <section id="how-it-works">
            <div class="container">
                <h2 class="section-title">¿Cómo funciona?</h2>
                <div id="steps" class="grid">

                    <article class="card step">
                        <div class="step-index">1</div>
                        <h3 class="step-title">Buscá</h3>
                        <p class="step-desc">Escribí el producto exacto o el modelo.</p>
                    </article>
                    <article class="card step">
                        <div class="step-index">2</div>
                        <h3 class="step-title">Compará</h3>
                        <p class="step-desc">Vas a ver el mismo producto en varias tiendas con su precio.</p>
                    </article>
                    <article class="card step">
                        <div class="step-index">3</div>
                        <h3 class="step-title">Comprá en la tienda oficial</h3>
                        <p class="step-desc">Te llevamos con un clic. Sin carrito aquí.</p>
                    </article>
                </div>
            </div>
        </section>


        {{-- Lo más buscado (grid de productos) --}}
        <section id="trending">
            <div class="container">
                <div id="trending-head">
                    <h2 class="section-title">Lo más buscado hoy</h2>
                    <small id="trending-note">Actualizado a diario</small>
                </div>

                @php
                    // Ejemplo de datos (reemplazar por datos reales del controlador)
                    $trendingProducts = [
                        [
                            'title' => 'PlayStation 5 Slim 1TB (CFI-2000)',
                            'specs' => '4K • 1TB • DualSense • Edición 2024',
                            'min' => 499,
                            'stores' => [
                                ['name' => 'Amazon', 'price' => 499, 'shipping' => 'Envío rápido', 'url' => '#'],
                                ['name' => 'eBay', 'price' => 509, 'shipping' => 'Varios vendedores', 'url' => '#'],
                                ['name' => 'Mercado Libre', 'price' => 515, 'shipping' => 'Full', 'url' => '#'],
                                ['name' => 'Best Buy', 'price' => 519, 'shipping' => 'Retiro en tienda', 'url' => '#'],
                            ],
                        ],
                        [
                            'title' => 'Nintendo Switch OLED (modelo 2021)',
                            'specs' => 'Pantalla OLED • 64GB • Joy-Con incluidos',
                            'min' => 289,
                            'stores' => [
                                ['name' => 'Amazon', 'price' => 289, 'shipping' => 'Prime', 'url' => '#'],
                                ['name' => 'eBay', 'price' => 295, 'shipping' => 'Varios vendedores', 'url' => '#'],
                                ['name' => 'Mercado Libre', 'price' => 302, 'shipping' => 'Full', 'url' => '#'],
                            ],
                        ],
                        [
                            'title' => 'SSD NVMe 1TB PCIe 4.0',
                            'specs' => 'Lectura 7,000 MB/s • M.2 2280',
                            'min' => 79,
                            'stores' => [
                                ['name' => 'Amazon', 'price' => 79, 'shipping' => '', 'url' => '#'],
                                ['name' => 'AliExpress', 'price' => 85, 'shipping' => '', 'url' => '#'],
                                ['name' => 'eBay', 'price' => 88, 'shipping' => '', 'url' => '#'],
                            ],
                        ],
                    ];
                @endphp

                <div id="product-grid" class="grid">
                    @foreach ($trendingProducts as $p)
                        <article class="card product" data-role="product">
                            <figure class="product-media">
                                <img src="{{ asset('imagenes/placeholder-4x3.png') }}" alt="{{ $p['title'] }}">
                                <button type="button" class="wishlist" aria-label="Añadir a wishlist" data-requires-auth="true"
                                    onclick="alert('Iniciá sesión para guardar en tu wishlist');">♡</button>
                            </figure>

                            <header class="product-head">
                                <h3 class="product-title">{{ $p['title'] }}</h3>
                                <span class="badge">Comparador</span>
                            </header>

                            <p class="product-specs">{{ $p['specs'] }}</p>

                            <div class="product-min">
                                <span>Desde</span>
                                <strong class="price">${{ number_format($p['min'], 0, ',', '.') }}</strong>
                            </div>

                            <ul class="store-list">
                                @foreach (array_slice($p['stores'], 0, 3) as $s)
                                    <li class="store">
                                        <div class="store-data">
                                            <span class="store-avatar">{{ strtoupper(substr($s['name'], 0, 2)) }}</span>
                                            <div class="store-name">{{ $s['name'] }}</div>
                                            @if (!empty($s['shipping']))
                                                <small class="store-ship">{{ $s['shipping'] }}</small>
                                            @endif
                                        </div>
                                        <div class="store-cta">
                                            <span class="store-price">${{ number_format($s['price'], 0, ',', '.') }}</span>
                                            <a href="#" class="button" target="_blank" rel="sponsored noopener"
                                                aria-label="Ir a la tienda {{ $s['name'] }}">Ir a la tienda ↗</a>
                                        </div>
                                    </li>
                                @endforeach

                                @if (count($p['stores']) > 3)
                                    <li class="more-offers">
                                        <button type="button">Ver {{ count($p['stores']) - 3 }} ofertas más</button>
                                    </li>
                                @endif
                            </ul>

                            <footer class="product-foot">
                                <small class="hint">Últimos 30 días</small>
                                <small class="hint">Compra en la tienda oficial</small>
                            </footer>
                        </article>
                    @endforeach
                </div>

                <p id="product-disclaimer">
                    Compra segura en la tienda oficial. Precios y stock pueden variar.
                </p>
            </div>
        </section>


    @endguest

@endsection
