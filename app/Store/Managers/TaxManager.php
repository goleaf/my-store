<?php

namespace App\Store\Managers;

use Illuminate\Support\Manager;
use App\Store\Drivers\SystemTaxDriver;

class TaxManager extends Manager
{
    public function createSystemDriver()
    {
        return $this->buildProvider(SystemTaxDriver::class);
    }

    /**
     * Build a tax provider instance.
     *
     * @param  string  $provider
     * @return mixed
     */
    public function buildProvider($provider)
    {
        return $this->container->make($provider);
    }

    public function getDefaultDriver()
    {
        return config('store.taxes.driver', 'system');
    }
}
