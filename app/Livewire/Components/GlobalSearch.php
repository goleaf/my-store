<?php

namespace App\Livewire\Components;

use App\Models\Product;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Component;

class GlobalSearch extends Component
{
    public string $term = '';
    public bool $showDropdown = false;

    public function updatedTerm(): void
    {
        if (strlen($this->term) >= 2) {
            $this->showDropdown = true;
        } else {
            $this->showDropdown = false;
        }
    }

    public function getResultsProperty(): Collection
    {
        if (strlen($this->term) < 2) {
            return collect();
        }

        // Search logic - using basic Eloquent for now, can be upgraded to Scout
        return Product::query()
            ->where(function ($query) {
                $query->where('attribute_data->name->value', 'like', '%' . $this->term . '%')
                    ->orWhere('attribute_data->description->value', 'like', '%' . $this->term . '%')
                    ->orWhereHas('variants', function ($q) {
                        $q->where('sku', 'like', '%' . $this->term . '%');
                    });
            })
            ->with(['variants.prices', 'thumbnail'])
            ->limit(8)
            ->get();
    }

    public function render(): View
    {
        return view('livewire.components.global-search', [
            'results' => $this->results,
        ]);
    }
}
