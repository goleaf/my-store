<?php

namespace Database\Seeders;

use App\Models\Collection;
use App\Models\FeaturedCategory;
use Illuminate\Database\Seeder;

class FeaturedCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (FeaturedCategory::query()->exists()) {
            return;
        }

        $collections = Collection::query()
            ->select([
                'id',
                'attribute_data',
            ])
            ->whereHas('defaultUrl')
            ->orderBy('id')
            ->limit(8)
            ->get();

        $featuredPrefixes = [
            'Trending in',
            'Fresh picks from',
            'Popular in',
            'Discover',
            'Editor picks for',
            'Everyday favorites from',
            'Best of',
            'Shop more from',
        ];

        $collections->each(function (Collection $collection, int $index) use ($featuredPrefixes): void {
            $collectionName = $collection->translateAttribute('name') ?? "Collection {$collection->id}";

            FeaturedCategory::factory()
                ->for($collection, 'collection')
                ->create([
                    'title' => "{$featuredPrefixes[$index]} {$collectionName}",
                    'sort_order' => $index + 1,
                    'is_active' => true,
                ]);
        });
    }
}
