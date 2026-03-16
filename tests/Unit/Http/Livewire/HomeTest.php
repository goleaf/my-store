<?php

use App\Base\Enums\HomeSectionType;
use App\FieldTypes\Text;
use App\Livewire\Home;
use App\Models\Collection;
use App\Models\Currency;
use App\Models\FeaturedCategory;
use App\Models\HomeBanner;
use App\Models\HomeHero;
use App\Models\HomeSection;
use App\Models\Language;
use Livewire\Livewire;

function createHomepageCollection(string $name = 'Fresh collection'): Collection
{
    Language::query()->firstWhere('default', true)
        ?? Language::factory()->create([
            'default' => true,
        ]);

    return Collection::factory()
        ->hasUrls(1, [
            'default' => true,
        ])
        ->create([
            'attribute_data' => collect([
                'name' => new Text($name),
            ]),
        ]);
}

test('component can mount', function () {
    Livewire::test(Home::class)
        ->assertViewIs('livewire.home');
});

test('translated heroes are visible on the homepage component', function () {
    $hero = HomeHero::factory()->create([
        'title' => ['en' => 'Fresh picks for tonight'],
        'subtitle' => ['en' => 'Delivered on your schedule'],
        'description' => ['en' => 'Quick grocery bundles for busy evenings.'],
        'link' => ['en' => '/shop'],
        'button_text' => ['en' => 'Start shopping'],
    ]);

    Livewire::test(Home::class)
        ->assertSee($hero->translate('title'))
        ->assertSee($hero->translate('subtitle'))
        ->assertSee($hero->translate('description'))
        ->assertSee($hero->translate('button_text'));
});

test('all admin-managed home page blocks render in the frontend', function () {
    Currency::factory()->create([
        'default' => true,
        'enabled' => true,
    ]);

    $collection = createHomepageCollection('Pantry Staples');

    FeaturedCategory::factory()
        ->for($collection, 'collection')
        ->create([
            'title' => 'Pantry picks',
        ]);

    HomeBanner::factory()->top()->create([
        'title' => 'Top banner title',
        'subtitle' => 'Top banner subtitle',
    ]);

    HomeBanner::factory()->middle()->create([
        'title' => 'Middle banner title',
        'subtitle' => 'Middle banner subtitle',
    ]);

    HomeBanner::factory()->bottom()->create([
        'title' => 'Bottom banner title',
        'subtitle' => 'Bottom banner subtitle',
    ]);

    HomeSection::factory()
        ->for($collection, 'collection')
        ->featuredItems()
        ->create([
            'title' => 'Featured shelf',
            'subtitle' => 'Editor picks',
            'type' => HomeSectionType::FeaturedItems,
        ]);

    Livewire::test(Home::class)
        ->assertSee('Pantry picks')
        ->assertSee('Pantry Staples')
        ->assertSee('Top banner title')
        ->assertSee('Middle banner title')
        ->assertSee('Bottom banner title')
        ->assertSee('Featured shelf')
        ->assertSee('Editor picks');
});
