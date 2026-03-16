<?php

namespace App\Livewire\Account;

use App\Http\Requests\Account\SaveAddressRequest;
use App\Models\Country;
use Illuminate\Contracts\View\View;
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
        'title' => 'Home',
        'first_name' => '',
        'last_name' => '',
        'company_name' => '',
        'tax_identifier' => '',
        'line_one' => '',
        'line_two' => '',
        'line_three' => '',
        'city' => '',
        'state' => '',
        'postcode' => '',
        'country_id' => null,
        'contact_email' => '',
        'contact_phone' => '',
        'shipping_default' => false,
        'billing_default' => false,
    ];

    public function mount(): void
    {
        $this->resetAddressForm();
    }

    public function editAddress(int $id): void
    {
        $address = Auth::user()->addresses()->findOrFail($id);
        $this->editingAddressId = $id;
        $this->address = array_merge($this->defaultAddress(), $address->only(array_keys($this->defaultAddress())));
        $this->showAddressForm = true;
    }

    public function deleteAddress(int $id): void
    {
        $addresses = Auth::user()->addresses();
        $address = $addresses->findOrFail($id);
        $wasDefault = (bool) ($address->shipping_default || $address->billing_default);

        $address->delete();

        if ($wasDefault) {
            $addresses->latest('id')->first()?->update([
                'shipping_default' => true,
                'billing_default' => true,
            ]);
        }

        session()->flash('status', 'Address deleted successfully.');
    }

    public function setDefault(int $id): void
    {
        $addresses = Auth::user()->addresses();

        if (! $addresses->whereKey($id)->exists()) {
            return;
        }

        $addresses->update([
            'shipping_default' => false,
            'billing_default' => false,
        ]);
        $addresses->whereKey($id)->update([
            'shipping_default' => true,
            'billing_default' => true,
        ]);

        session()->flash('status', 'Default address updated successfully.');
    }

    public function saveAddress(): void
    {
        $request = new SaveAddressRequest;
        $validated = $request->validatePayload([
            'address' => $this->address,
        ]);
        $addressData = $validated['address'];

        $customer = Auth::user();
        $addressData['billing_default'] = (bool) $addressData['shipping_default'];

        if ($addressData['shipping_default']) {
            $customer->addresses()->update([
                'shipping_default' => false,
                'billing_default' => false,
            ]);
        }

        if ($this->editingAddressId) {
            $customer->addresses()->findOrFail($this->editingAddressId)->update($addressData);
            session()->flash('status', 'Address updated successfully.');
        } else {
            if (! $customer->addresses()->exists()) {
                $addressData['shipping_default'] = true;
                $addressData['billing_default'] = true;
            }

            $customer->addresses()->create($addressData);
            session()->flash('status', 'Address created successfully.');
        }

        $this->resetAddressForm();
    }

    public function render(): View
    {
        return view('livewire.account.addresses', [
            'addresses' => Auth::user()->addresses()->with('country')->latest()->get(),
            'countries' => Country::query()->orderBy('name')->pluck('name', 'id'),
        ]);
    }

    private function resetAddressForm(): void
    {
        $this->showAddressForm = false;
        $this->editingAddressId = null;
        $this->address = $this->defaultAddress();
    }

    private function defaultAddress(): array
    {
        return [
            'title' => 'Home',
            'first_name' => '',
            'last_name' => '',
            'company_name' => '',
            'tax_identifier' => '',
            'line_one' => '',
            'line_two' => '',
            'line_three' => '',
            'city' => '',
            'state' => '',
            'postcode' => '',
            'country_id' => Country::query()->where('iso3', 'USA')->value('id'),
            'contact_email' => Auth::user()?->email,
            'contact_phone' => Auth::user()?->phone ?? '',
            'shipping_default' => false,
            'billing_default' => false,
        ];
    }
}
