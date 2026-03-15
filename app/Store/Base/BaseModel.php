<?php

namespace App\Store\Base;

use Illuminate\Database\Eloquent\Model;
use App\Store\Base\Traits\HasModelExtending;

abstract class BaseModel extends Model
{
    use HasModelExtending;

    /**
     * Create a new instance of the Model.
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setTable(config('store.database.table_prefix').$this->getTable());

        if ($connection = config('store.database.connection')) {
            $this->setConnection($connection);
        }
    }
}
