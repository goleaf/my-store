<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Base\BaseModel;
use App\Base\Traits\HasMacros;
use App\Base\Traits\LogsActivity;
use App\Database\Factories\CollectionGroupFactory;

/**
 * @property int $id
 * @property string $name
 * @property string $handle
 * @property ?\Illuminate\Support\Carbon $created_at
 * @property ?\Illuminate\Support\Carbon $updated_at
 */
class CollectionGroup extends BaseModel implements Contracts\CollectionGroup
{
    use HasFactory;
    use HasMacros;
    use LogsActivity;

    protected $guarded = [];

    /**
     * Return a new factory instance for the model.
     */
    protected static function newFactory()
    {
        return CollectionGroupFactory::new();
    }

    public function collections(): HasMany
    {
        return $this->hasMany(Collection::modelClass());
    }
}
