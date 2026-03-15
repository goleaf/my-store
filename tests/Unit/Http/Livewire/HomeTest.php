<?php

use App\Livewire\Home;
use Livewire\Livewire;

test('component can mount', function () {
    Livewire::test(Home::class)
        ->assertViewIs('livewire.home');
});