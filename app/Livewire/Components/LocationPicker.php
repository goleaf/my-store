<?php

namespace App\Livewire\Components;

use App\Models\Store\Models\DeliveryZone;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;
use Livewire\Component;

class LocationPicker extends Component
{
    public string $search = '';
    public ?int $selectedZoneId = null;

    public function mount(): void
    {
        $this->selectedZoneId = Session::get('delivery_zone_id');
    }

    public function selectZone(int $zoneId): void
    {
        Session::put('delivery_zone_id', $zoneId);
        $this->selectedZoneId = $zoneId;
        $this->dispatch('locationUpdated');
    }

    public function clearLocation(): void
    {
        Session::forget('delivery_zone_id');
        $this->selectedZoneId = null;
        $this->dispatch('locationUpdated');
    }

    public function getZonesProperty(): Collection
    {
        return DeliveryZone::query()
            ->whereIsActive(true)
            ->when(filled($this->search), function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->orderBy('name')
            ->get();
    }

    public function getSelectedZoneProperty(): ?DeliveryZone
    {
        if (!$this->selectedZoneId) {
            return null;
        }

        return DeliveryZone::find($this->selectedZoneId);
    }

    public function render(): View
    {
        return view('livewire.components.location-picker', [
            'zones' => $this->zones,
            'selectedZone' => $this->selectedZone,
        ]);
    }
}
