<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use App\Base\BaseModel;
use App\Base\Traits\HasMacros;
use App\Base\Traits\LogsActivity;
use App\Database\Factories\TagFactory;
use App\Facades\DB;

/**
 * @property int $id
 * @property string $value
 * @property ?\Illuminate\Support\Carbon $created_at
 * @property ?\Illuminate\Support\Carbon $updated_at
 */
class Tag extends BaseModel implements Contracts\Tag
{
    use HasFactory;
    use HasMacros;
    use LogsActivity;

    public static function booted(): void
    {
        static::deleting(function (self $tag) {
            DB::table(config('store.database.table_prefix').'taggables')
                ->where('tag_id', $tag->id)
                ->delete();
        });
    }

    /**
     * Return a new factory instance for the model.
     */
    protected static function newFactory()
    {
        return TagFactory::new();
    }

    /**
     * Define which attributes should be
     * protected from mass assignment.
     *
     * @var array
     */
    protected $guarded = [];

    public function taggable(): MorphTo
    {
        return $this->morphTo();
    }
}
