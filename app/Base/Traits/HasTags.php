<?php

namespace App\Store\Base\Traits;

use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Collection;
use App\Store\Jobs\SyncTags;
use App\Store\Models\Tag;

trait HasTags
{
    /**
     * Get the tags
     */
    public function tags(): MorphToMany
    {
        $prefix = config('store.database.table_prefix');

        return $this->morphToMany(
            Tag::modelClass(),
            'taggable',
            "{$prefix}taggables"
        )->withTimestamps();
    }

    public function syncTags(Collection $tags)
    {
        SyncTags::dispatch($this, $tags);
    }
}
