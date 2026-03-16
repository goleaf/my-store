<?php

namespace App\Livewire\Components;

use App\Models\Store\Models\Announcement;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;
use Livewire\Component;

class AnnouncementBar extends Component
{
    public bool $isVisible = true;

    public function mount(): void
    {
        if (Session::get('announcement_dismissed')) {
            $this->isVisible = false;
        }
    }

    public function dismiss(): void
    {
        Session::put('announcement_dismissed', true);
        $this->isVisible = false;
    }

    public function render(): View
    {
        $announcement = Announcement::query()
            ->whereIsActive(true)
            ->where(function ($query) {
                $query->whereNull('starts_at')
                    ->orWhere('starts_at', '<=', now());
            })
            ->where(function ($query) {
                $query->whereNull('ends_at')
                    ->orWhere('ends_at', '>=', now());
            })
            ->latest()
            ->first();

        return view('livewire.components.announcement-bar', [
            'announcement' => $announcement,
        ]);
    }
}
