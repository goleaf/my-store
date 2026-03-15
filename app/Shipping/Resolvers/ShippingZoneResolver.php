<?php

namespace App\Shipping\Resolvers;

use Illuminate\Support\Collection;
use App\Store\Models\Contracts\Country as CountryContract;
use App\Store\Models\Contracts\State as StateContract;
use App\Store\Models\Country;
use App\Store\Models\State;
use App\Shipping\DataTransferObjects\PostcodeLookup;
use App\Shipping\Models\ShippingZone;

class ShippingZoneResolver
{
    /**
     * The country to use when resolving zones.
     */
    protected ?CountryContract $country = null;

    /**
     * The state to use when resolving zones.
     */
    protected ?State $state = null;

    /**
     * The postcode lookup to use when resolving zones.
     */
    protected ?PostcodeLookup $postcodeLookup = null;

    /**
     * The type of zones we want to query.
     */
    protected Collection $types;

    /**
     * Initialise the resolver.
     */
    public function __construct()
    {
        $this->types = collect();
    }

    /**
     * Set the country.
     */
    public function country(?CountryContract $country = null): self
    {
        $this->country = $country;
        $this->types->push('countries');

        return $this;
    }

    /**
     * Set the state.
     */
    public function state(?StateContract $state = null): self
    {
        $this->state = $state;
        $this->types->push('states');

        return $this;
    }

    /**
     * Set the postcode to use when resolving.
     */
    public function postcode(PostcodeLookup $postcodeLookup): self
    {
        $this->postcodeLookup = $postcodeLookup;
        $this->types->push('postcodes');

        return $this;
    }

    /**
     * Return the shipping zones based on the criteria.
     */
    public function get(): Collection
    {
        $query = ShippingZone::query()->whereType('unrestricted');

        $query->orWhere(function ($builder) {
            if ($this->country) {
                $builder->orWhere(function ($qb) {
                    $qb->whereHas('countries', function ($query) {
                        $query->where('country_id', $this->country->id);
                    })->whereType('countries');
                });
            }

            if ($this->state) {
                $builder->orWhere(function ($qb) {
                    $qb->whereHas('states', function ($query) {
                        $query->where('state_id', $this->state->id);
                    })->whereType('states');
                });
            }

            if ($this->postcodeLookup) {
                $builder->orWhere(function ($qb) {
                    $qb->whereHas('postcodes', function ($query) {
                        $postcodeParts = (new PostcodeResolver)->getParts(
                            $this->postcodeLookup->postcode
                        );
                        $query->whereIn('postcode', $postcodeParts);
                    })->where(function ($qb) {
                        $qb->whereHas('countries', function ($query) {
                            $query->where('country_id', $this->postcodeLookup->country->id);
                        });
                    })->whereType('postcodes');
                })->orWhere(function ($qb) {
                    $qb->whereHas('countries', function ($query) {
                        $query->where('country_id', $this->postcodeLookup->country->id);
                    })->whereType('countries');
                });
            }
        });

        return $query->get();
    }
}
