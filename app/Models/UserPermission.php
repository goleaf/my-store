<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Base\BaseModel;
use App\Base\Traits\HasMacros;

class UserPermission extends BaseModel implements Contracts\UserPermission
{
    use HasMacros;

    protected $fillable = ['handle'];

    /**
     * Return the user relationship.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(config('auth.providers.users.model'));
    }
}
