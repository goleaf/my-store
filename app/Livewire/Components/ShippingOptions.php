<?php

namespace App\Livewire\Components;

use App\Facades\CartSession;
use App\Facades\ShippingManifest;
use App\Http\Requests\Checkout\SelectShippingOptionRequest;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Component;

class ShippingOptions extends Component
{
    /**
     * The chosen shipping option.
     */
    public ?string $chosenOption = null;

    public function mount(): void
    {
        if ($shippingOption = $this->shippingAddress?->shipping_option) {
            $option = $this->shippingOptions->first(function ($opt) use ($shippingOption) {
                return $opt->getIdentifier() == $shippingOption;
            });
            $this->chosenOption = $option?->getIdentifier();
        }
    }

    /**
     * Return available shipping options.
     */
    public function getShippingOptionsProperty(): Collection
    {
        return ShippingManifest::getOptions(
            CartSession::current()
        );
    }

    /**
     * Save the shipping option.
     */
    public function save(): void
    {
        $request = new SelectShippingOptionRequest;
        $request->validatePayload([
            'chosenOption' => $this->chosenOption,
        ]);

        $option = $this->shippingOptions->first(fn ($option) => $option->getIdentifier() == $this->chosenOption);

        CartSession::setShippingOption($option);

        $this->dispatch('selectedShippingOption');
    }

    /**
     * Return whether we have a shipping address.
     */
    public function getShippingAddressProperty()
    {
        return CartSession::getCart()->shippingAddress;
    }

    public function render(): View
    {
        return view('livewire.components.shipping-options');
    }
}
