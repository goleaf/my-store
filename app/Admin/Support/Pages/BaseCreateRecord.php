<?php

namespace App\Admin\Support\Pages;

use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

abstract class BaseCreateRecord extends CreateRecord
{
    use Concerns\ExtendsFooterWidgets;
    use Concerns\ExtendsFormActions;
    use Concerns\ExtendsForms;
    use Concerns\ExtendsHeaderActions;
    use Concerns\ExtendsHeaderWidgets;
    use Concerns\ExtendsHeadings;
    use \App\Admin\Support\Concerns\CallsHooks;

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
