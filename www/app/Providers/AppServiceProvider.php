<?php

namespace App\Providers;

use App\Repositories\StoreRepositoryInterface;
use App\Repositories\StoreRepository;
use App\Http\Integrations\StoreApiClientInterface;
use App\Http\Integrations\GenericStoreApiClient;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Bindings de interfaces con sus implementaciones
        $this->app->bind(StoreRepositoryInterface::class, StoreRepository::class);
        $this->app->bind(StoreApiClientInterface::class, GenericStoreApiClient::class);
    }

    public function boot(): void
    {
        //
    }
}