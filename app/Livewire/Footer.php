<?php
namespace App\Livewire;
use App\Store\Models\Collection;
use App\Store\Models\Brand;
use Livewire\Component;
class Footer extends Component
{
    public function getCollectionsProperty()
    {
        return Collection::with(['defaultUrl'])->get()->toTree();
    }
    public function getBrandsProperty()
    {
        return Brand::with(['defaultUrl'])->get();
    }
    public function render()
    {
        return view('livewire.footer');
    }
}
