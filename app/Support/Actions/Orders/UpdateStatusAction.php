<?php

namespace App\Support\Actions\Orders;

use App\Models\Order;
use App\Support\Actions\Traits\UpdatesOrderStatus;
use Filament\Actions\Action;
use Filament\Support\Enums\Width;

class UpdateStatusAction extends Action
{
    use UpdatesOrderStatus;

    protected function setUp(): void
    {
        parent::setUp();

        $this->label(
            __('admin::actions.orders.update_status.label')
        );

        $this->modalWidth(Width::TwoExtraLarge);

        $this->form(
            $this->getFormSteps()
        );

        $this->action(
            fn (Order $record, array $data) => $this->updateStatus($record, $data)
        );
    }
}
