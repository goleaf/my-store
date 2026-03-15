<?php

namespace App\Support\Pages;

use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

abstract class BaseCreateRecord extends CreateRecord
{
    use \App\Support\Pages\Concerns\ExtendsFooterWidgets;
    use \App\Support\Pages\Concerns\ExtendsFormActions;
    use \App\Support\Pages\Concerns\ExtendsForms;
    use \App\Support\Pages\Concerns\ExtendsHeaderActions;
    use \App\Support\Pages\Concerns\ExtendsHeaderWidgets;
    use \App\Support\Pages\Concerns\ExtendsHeadings;
    use \App\Support\Concerns\CallsHooks;

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
