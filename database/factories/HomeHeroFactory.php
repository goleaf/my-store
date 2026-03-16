<?php

namespace Database\Factories;

use App\Models\HomeHero;
use App\Models\Language;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<HomeHero>
 */
class HomeHeroFactory extends Factory
{
    protected $model = HomeHero::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = Str::title($this->faker->words(3, true));
        $subtitle = Str::title($this->faker->words(2, true));
        $description = $this->faker->sentence(12);
        $buttonText = Str::title($this->faker->words(2, true));
        $slug = $this->faker->unique()->slug(2);

        return [
            'title' => $this->translatedValue($title),
            'subtitle' => $this->translatedValue($subtitle),
            'description' => $this->translatedValue($description),
            'link' => $this->translatedValue("/collections/{$slug}", true),
            'button_text' => $this->translatedValue($buttonText),
            'image' => null,
            'sort_order' => $this->faker->numberBetween(1, 50),
            'is_active' => true,
        ];
    }

    private function translatedValue(string $englishValue, bool $repeatAcrossLocales = false): array
    {
        return collect($this->languageCodes())->mapWithKeys(
            fn (string $languageCode): array => [
                $languageCode => $repeatAcrossLocales || $languageCode === 'en'
                    ? $englishValue
                    : "{$englishValue} ({$languageCode})",
            ],
        )->all();
    }

    private function languageCodes(): array
    {
        $languageCodes = Language::query()
            ->orderByDesc('default')
            ->pluck('code')
            ->filter()
            ->unique()
            ->values()
            ->all();

        return $languageCodes !== [] ? $languageCodes : ['en'];
    }
}
