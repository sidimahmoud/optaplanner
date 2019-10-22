<?php

namespace DigitalTolk\OptaplannerAdapter;

use Illuminate\Support\ServiceProvider;
use DigitalTolk\OptaplannerAdapter\Contracts\Adapters\OptaplannerServiceAdapter as OptaplannerServiceAdapterContract;
use DigitalTolk\OptaplannerAdapter\Adapters\OptaplannerServiceAdapter;
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
