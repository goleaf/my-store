<?php

namespace App\Livewire\Components;

use App\Base\Enums\ProductStatus;
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
        $this->term = trim($this->term);

        if (strlen($this->term) >= 2) {
            $this->showDropdown = true;
        } else {
            $this->showDropdown = false;
        }
    }

    public function search(): void
    {
        if (strlen($this->term) < 2) {
            return;
        }

        $this->showDropdown = false;

        $this->redirect(route('search.view', ['term' => $this->term]), navigate: true);
    }

    public function getResultsProperty(): Collection
    {
        if (strlen($this->term) < 2) {
            return collect();
        }

        $scoutDriver = config('scout.driver');

        if (filled($scoutDriver) && $scoutDriver !== 'null') {
            return Product::search($this->term)
                ->query(fn ($builder) => $builder
                    ->whereStatus(ProductStatus::Published)
                    ->with(['defaultUrl', 'variants.prices', 'thumbnail'])
                    ->limit(8)
                )
                ->get();
        }

        return Product::query()
            ->whereStatus(ProductStatus::Published)
            ->where(function ($query) {
                $query->where('attribute_data->name->value', 'like', '%'.$this->term.'%')
                    ->orWhere('attribute_data->description->value', 'like', '%'.$this->term.'%')
                    ->orWhereHas('variants', function ($q) {
                        $q->where('sku', 'like', '%'.$this->term.'%');
                    });
            })
            ->with(['defaultUrl', 'variants.prices', 'thumbnail'])
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
