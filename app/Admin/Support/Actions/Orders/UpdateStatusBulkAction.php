<?php

namespace App\Admin\Support\Actions\Orders;

use Filament\Support\Enums\Width;
use Filament\Tables\Actions\BulkAction;
use Illuminate\Database\Eloquent\Collection;
use App\Admin\Support\Actions\Traits\UpdatesOrderStatus;
use App\Store\Facades\DB;

class UpdateStatusBulkAction extends BulkAction
{
    use UpdatesOrderStatus;

    protected function setUp(): void
    {
        parent::setUp();

        $this->label(
            __('admin::actions.orders.update_status.label')
        );

        $this->modalWidth(Width::TwoExtraLarge);

        $this->form([
            static::getStatusSelectInput(),
            static::getMailersCheckboxInput(),
            static::getAdditionalContentInput(),
            static::getAdditionalEmailInput(),
        ]);

        $this->action(
            function (Collection $records, array $data) {
                DB::beginTransaction();
                foreach ($records as $record) {
                    $this->updateStatus($record, $data);
                }
                DB::commit();
            }
        );
    }
}
