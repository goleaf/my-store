<?php

namespace App\Admin\Filament\Resources\CustomerResource\Pages;

use App\Admin\Filament\Resources\CustomerResource;
use App\Admin\Support\Pages\BaseCreateRecord;

class CreateCustomer extends BaseCreateRecord
{
    protected static string $resource = CustomerResource::class;
}
