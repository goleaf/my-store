<?php

namespace App\Base\Traits;

use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Collection;
use App\Jobs\SyncTags;
use App\Models\Tag;

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
