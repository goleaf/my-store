<?php

namespace App\Livewire\Account;

use App\Models\Address;
use App\Models\Country;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Addresses')]
class Addresses extends Component
{
    public bool $showAddressForm = false;
    public ?int $editingAddressId = null;

    public array $address = [
        'first_name' => '',
        'last_name' => '',
        'line_one' => '',
        'line_two' => '',
        'city' => '',
        'state' => '',
        'postcode' => '',
        'country_id' => '',
        'contact_phone' => '',
        'shipping_default' => false,
        'billing_default' => false,
    ];

    public function mount(): void
    {
        $this->address['country_id'] = Country::where('iso2', 'US')->first()?->id ?? Country::first()?->id;
    }

    public function editAddress(int $id): void
    {
        $address = Auth::user()->latestCustomer()->addresses()->findOrFail($id);
        $this->editingAddressId = $id;
        $this->address = $address->toArray();
        $this->showAddressForm = true;
    }

    public function deleteAddress(int $id): void
    {
        Auth::user()->latestCustomer()->addresses()->findOrFail($id)->delete();
        session()->flash('status', 'Address deleted successfully.');
    }

    public function saveAddress(): void
    {
        $this->validate([
            'address.first_name' => 'required|string|max:255',
            'address.last_name' => 'required|string|max:255',
            'address.line_one' => 'required|string|max:255',
            'address.line_two' => 'nullable|string|max:255',
            'address.city' => 'required|string|max:255',
            'address.state' => 'nullable|string|max:255',
            'address.postcode' => 'required|string|max:20',
            'address.country_id' => 'required|exists:store_countries,id',
            'address.contact_phone' => 'nullable|string|max:20',
        ]);

        $customer = Auth::user()->latestCustomer();

        if ($this->editingAddressId) {
            $customer->addresses()->findOrFail($this->editingAddressId)->update($this->address);
            session()->flash('status', 'Address updated successfully.');
        } else {
            $customer->addresses()->create($this->address);
            session()->flash('status', 'Address created successfully.');
        }

        $this->reset(['showAddressForm', 'editingAddressId', 'address']);
        $this->mount();
    }

    public function render()
    {
        $addresses = Auth::user()->latestCustomer()?->addresses ?? collect();
        $countries = Country::all();

        return view('livewire.account.addresses', [
            'addresses' => $addresses,
            'countries' => $countries,
        ]);
    }
}
