<?php

namespace Database\Seeders;

use App\Models\HomeHero;
use App\Models\Language;
use Illuminate\Database\Seeder;

class HomeHeroSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (HomeHero::query()->exists()) {
            return;
        }

        HomeHero::factory()->create([
            'title' => $this->translatedValue('Fresh groceries, delivered fast'),
            'subtitle' => $this->translatedValue('Same-day pantry restock'),
            'description' => $this->translatedValue('Build your weekly basket with produce, snacks, and kitchen staples picked for quick delivery.'),
            'link' => $this->translatedValue('/shop', true),
            'button_text' => $this->translatedValue('Shop fresh picks'),
            'sort_order' => 1,
        ]);

        HomeHero::factory()->create([
            'title' => $this->translatedValue('Weekend specials worth stocking up on'),
            'subtitle' => $this->translatedValue('Limited-time family favorites'),
            'description' => $this->translatedValue('Discover bundle offers on breakfast essentials, frozen meals, and cupboard must-haves before they are gone.'),
            'link' => $this->translatedValue('/collections/sale', true),
            'button_text' => $this->translatedValue('View weekend deals'),
            'sort_order' => 2,
        ]);

        HomeHero::factory()->create([
            'title' => $this->translatedValue('Healthy choices for every routine'),
            'subtitle' => $this->translatedValue('Curated by category'),
            'description' => $this->translatedValue('Browse fruits, vegetables, proteins, and better-for-you snacks organized to make repeat ordering effortless.'),
            'link' => $this->translatedValue('/collections/healthy-living', true),
            'button_text' => $this->translatedValue('Explore healthy picks'),
            'sort_order' => 3,
        ]);
    }

    private function translatedValue(string $englishValue, bool $repeatAcrossLocales = false): array
    {
        $languageCodes = Language::query()
            ->orderByDesc('default')
            ->pluck('code')
            ->filter()
            ->unique()
            ->values()
            ->all();

        if ($languageCodes === []) {
            $languageCodes = ['en'];
        }

        return collect($languageCodes)->mapWithKeys(
            fn (string $languageCode): array => [
                $languageCode => $repeatAcrossLocales || $languageCode === 'en'
                    ? $englishValue
                    : "{$englishValue} ({$languageCode})",
            ],
        )->all();
    }
}
