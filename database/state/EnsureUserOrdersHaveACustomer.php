<?php

namespace App\Database\State;

use Illuminate\Support\Facades\Schema;
use App\Facades\DB;
use App\Models\Order;

class EnsureUserOrdersHaveACustomer
{
    public function prepare()
    {
        //
    }

    public function run()
    {
        if (! $this->canRun()) {
            return;
        }

        // Get any orders which have a user but not a customer id
        $orders = Order::with('user.customers')
            ->whereNull('customer_id')
            ->whereNotNull('user_id')
            ->get();

        DB::transaction(function () use ($orders) {
            foreach ($orders as $order) {
                $customer = $order->user->customers->first();
                $order->update([
                    'customer_id' => $customer?->id,
                ]);
            }
        });
    }

    protected function canRun()
    {
        $prefix = config('store.database.table_prefix');

        return Schema::hasTable("{$prefix}orders");
    }
}
