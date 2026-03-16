<?php

namespace App\Support\Pages;

use App\Support\Pages\Concerns\ExtendsFooterWidgets;
use App\Support\Pages\Concerns\ExtendsFormActions;
use App\Support\Pages\Concerns\ExtendsForms;
use App\Support\Pages\Concerns\ExtendsHeaderActions;
use App\Support\Pages\Concerns\ExtendsHeaderWidgets;
use App\Support\Pages\Concerns\ExtendsHeadings;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use App\Support\Concerns\CallsHooks;

abstract class BaseCreateRecord extends CreateRecord
{
    use ExtendsFooterWidgets;
    use ExtendsFormActions;
    use ExtendsForms;
    use ExtendsHeaderActions;
    use ExtendsHeaderWidgets;
    use ExtendsHeadings;
    use CallsHooks;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return $this->callStoreHook('beforeCreate', $data);
    }

    protected function handleRecordCreation(array $data): Model
    {
        $data = $this->callStoreHook('beforeCreation', $data);

        $record = parent::handleRecordCreation($data);

        return $this->callStoreHook('afterCreation', $record, $data);
    }
}
