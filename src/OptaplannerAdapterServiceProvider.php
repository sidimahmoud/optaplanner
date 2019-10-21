<?php

namespace src;

use Illuminate\Support\ServiceProvider;
use src\Contracts\Adapters\OptaplannerServiceAdapter as OptaplannerServiceAdapterContract;
use src\Adapters\OptaplannerServiceAdapter;
use Laravel\Passport\ClientRepository;

class OptaplannerAdapterServiceProvider extends ServiceProvider
{
    /**
     * Publish config
     */
    public function boot()
    {
        
    }

    /**
     * Bind adapter
     */
    public function register()
    {
        $this->app->bind(
            OptaplannerServiceAdapterContract::class,
            OptaplannerServiceAdapter::class
        );
    }
}
