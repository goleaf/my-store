<?php

namespace App\Livewire\Components;

use App\Facades\CartSession;
use App\Http\Requests\Checkout\SaveAddressRequest;
use App\Models\Cart;
use App\Models\CartAddress;
use App\Models\Country;
use Illuminate\View\View;
use Livewire\Component;

class CheckoutAddress extends Component
{
    /**
     * The type of address.
     */
    public string $type = 'billing';

    /**
     * The ID of the cart.
     */
    public Cart $cart;

    /**
     * Whether we are currently editing the address.
     */
    public bool $editing = false;

    /**
     * The checkout address model.
     */
    public CartAddress $address;

    /**
     * Whether billing is the same as shipping.
     */
    public bool $shippingIsBilling = false;

    protected $listeners = [
        'refreshAddress',
    ];

    public function mount(): void
    {
        $this->cart = CartSession::current();

        $this->address = $this->cart->addresses->first(fn ($add) => $add->type == $this->type) ?: new CartAddress;

        // If we have an existing ID then it should already be valid and ready to go.
        $this->editing = (bool) ! $this->address->id;
    }

    public function rules(): array
    {
        return (new SaveAddressRequest)
            ->withPrefix('address')
            ->rules();
    }

    /**
     * Save the cart address.
     */
    public function save(): void
    {
        $request = (new SaveAddressRequest)->withPrefix('address');
        $validatedData = $this->validate($request->rules());

        if ($this->type == 'billing') {
            $this->cart->setBillingAddress($this->address);
        }

        if ($this->type == 'shipping') {
            $this->cart->setShippingAddress($this->address);
            if ($this->shippingIsBilling) {
                // Do we already have a billing address?
                if ($billing = $this->cart->billingAddress) {
                    $billing->fill($validatedData['address']);
                    $this->cart->setBillingAddress($billing);
                } else {
                    $address = $this->address->only(
                        $this->address->getFillable()
                    );
                    $this->cart->setBillingAddress($address);
                }
            }
        }

        $this->editing = false;

        $this->emitUp('addressUpdated');
    }

    public function refreshAddress(): void
    {
        if ($address = $this->cart->addresses()->whereType($this->type)->first()) {
            $this->address = $address;
            $this->editing = false;
        }
    }

    public function getCountriesProperty()
    {
        return Country::whereIn('iso3', ['GBR', 'USA'])->get();
    }

    public function render(): View
    {
        return view('livewire.components.checkout-address');
    }
}
