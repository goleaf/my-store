<?php

namespace App\Support\Pages;

use App\Support\Pages\Concerns\ExtendsFooterWidgets;
use App\Support\Pages\Concerns\ExtendsFormActions;
use App\Support\Pages\Concerns\ExtendsForms;
use App\Support\Pages\Concerns\ExtendsHeaderActions;
use App\Support\Pages\Concerns\ExtendsHeaderWidgets;
use App\Support\Pages\Concerns\ExtendsHeadings;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use App\Support\Concerns\CallsHooks;

abstract class BaseEditRecord extends EditRecord
{
    use ExtendsFooterWidgets;
    use ExtendsFormActions;
    use ExtendsForms;
    use ExtendsHeaderActions;
    use ExtendsHeaderWidgets;
    use ExtendsHeadings;
    use CallsHooks;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        return $this->callStoreHook('beforeFill', $data);
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        return $this->callStoreHook('beforeSave', $data);
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $data = $this->callStoreHook('beforeUpdate', $data, $record);

        $record = parent::handleRecordUpdate($record, $data);

        return $this->callStoreHook('afterUpdate', $record, $data);
    }

    public function afterSave()
    {
        sync_with_search(
            $this->getRecord()
        );
    }
}
