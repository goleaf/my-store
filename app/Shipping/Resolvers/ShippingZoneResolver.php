<?php

namespace App\Shipping\Resolvers;

use App\Models\State;
use App\Shipping\Enums\ShippingZoneType;
use App\Shipping\DataTransferObjects\PostcodeLookup;
use App\Shipping\Models\ShippingZone;
use App\Models\Contracts;
use App\Models\Contracts\Country;
use Illuminate\Support\Collection;

class ShippingZoneResolver
{
    /**
     * The country to use when resolving zones.
     */
    protected ?Country $country = null;

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
    public function country(?Country $country = null): self
    {
        $this->country = $country;
        $this->types->push(ShippingZoneType::Countries->value);

        return $this;
    }

    /**
     * Set the state.
     */
    public function state(?Contracts\State $state = null): self
    {
        $this->state = $state;
        $this->types->push(ShippingZoneType::States->value);

        return $this;
    }

    /**
     * Set the postcode to use when resolving.
     */
    public function postcode(PostcodeLookup $postcodeLookup): self
    {
        $this->postcodeLookup = $postcodeLookup;
        $this->types->push(ShippingZoneType::Postcodes->value);

        return $this;
    }

    /**
     * Return the shipping zones based on the criteria.
     */
    public function get(): Collection
    {
        $query = ShippingZone::query()->where('type', ShippingZoneType::Unrestricted->value);

        $query->orWhere(function ($builder) {
            if ($this->country) {
                $builder->orWhere(function ($qb) {
                    $qb->whereHas('countries', function ($query) {
                        $query->where('country_id', $this->country->id);
                    })->where('type', ShippingZoneType::Countries->value);
                });
            }

            if ($this->state) {
                $builder->orWhere(function ($qb) {
                    $qb->whereHas('states', function ($query) {
                        $query->where('state_id', $this->state->id);
                    })->where('type', ShippingZoneType::States->value);
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
                    })->where('type', ShippingZoneType::Postcodes->value);
                })->orWhere(function ($qb) {
                    $qb->whereHas('countries', function ($query) {
                        $query->where('country_id', $this->postcodeLookup->country->id);
                    })->where('type', ShippingZoneType::Countries->value);
                });
            }
        });

        return $query->get();
    }
}
