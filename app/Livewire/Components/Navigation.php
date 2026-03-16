<?php

namespace App\Livewire\Components;

use Illuminate\View\View;
use Livewire\Component;
use App\Models\Collection;
use App\Models\Brand;

class Navigation extends Component
{
    /**
     * The search term for the search input.
     *
     * @var string
     */
    public $term = null;

    /**
     * {@inheritDoc}
     */
    protected $queryString = [
        'term',
    ];

    /**
     * Return the collections in a tree.
     */
    public function getCollectionsProperty()
    {
        return Collection::with(['defaultUrl'])->get()->toTree();
    }

    /**
     * Return the brands.
     */
    public function getBrandsProperty()
    {
        return Brand::with(['defaultUrl'])->get();
    }

    public function render(): View
    {
        return view('livewire.components.navigation');
    }
}
