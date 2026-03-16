<?php
namespace App\Livewire\Components;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Component;
use App\Store\Facades\CartSession;
use App\Store\Models\Currency;
class CurrencySwitcher extends Component
{
    public function getCurrenciesProperty(): Collection
    {
        return Currency::whereEnabled(true)->get();
    }
    public function getCurrencyProperty(): Currency
    {
        return CartSession::getCurrency();
    }
    public function setCurrency($currencyId): void
    {
        $currency = Currency::find($currencyId);
        CartSession::setCurrency($currency);
        $this->dispatch('currencyUpdated');
        $this->redirect(request()->header('Referer'));
    }
    public function render(): View
    {
        return view('livewire.components.currency-switcher');
    }
}
