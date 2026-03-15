<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use App\Store\FieldTypes\Text;
use App\Store\FieldTypes\TranslatedText;
use App\Store\Models\Collection;
use App\Store\Models\CollectionGroup;

class CollectionSeeder extends AbstractSeeder
{
    /**
     * Run the database seeds.
     *
     */
    public function run(): void
    {
        $collections = $this->getSeedData('collections');

        $collectionGroup = CollectionGroup::first();

        DB::transaction(function () use ($collections, $collectionGroup) {
            foreach ($collections as $collection) {
                Collection::create([
                    'collection_group_id' => $collectionGroup->id,
                    'attribute_data' => [
                        'name' => new TranslatedText([
                            'en' => new Text($collection->name),
                        ]),
                        'description' => new TranslatedText([
                            'en' => new Text($collection->description),
                        ]),
                    ],
                ]);
            }
        });
    }
}
