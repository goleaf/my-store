<?php

use App\Livewire\SearchPage;
use Livewire\Livewire;

test('component can mount', function () {
    Livewire::test(SearchPage::class)
        ->assertViewIs('livewire.search-page');
});

test('term property is nullable', function () {
    $component = Livewire::test(SearchPage::class);

    expect($component->get('term'))->toBeNull();
});

test('results property returns paginator', function () {
    Livewire::test(SearchPage::class)
        ->assertViewIs('livewire.search-page');

    $component = Livewire::test(SearchPage::class, ['term' => 'test']);
    $results = $component->get('results');

    expect($results)->toBeInstanceOf(\Illuminate\Pagination\LengthAwarePaginator::class);
});
