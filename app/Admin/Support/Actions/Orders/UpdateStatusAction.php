<?php

namespace App\Admin\Support\Actions\Orders;

use Filament\Actions\Action;
use Filament\Support\Enums\Width;
use App\Admin\Support\Actions\Traits\UpdatesOrderStatus;
use App\Store\Models\Order;

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
