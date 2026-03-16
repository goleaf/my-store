<?php

namespace App\Actions\Carts;

use App\Actions\AbstractAction;
use App\Facades\DB;
use App\Models\CartLine;

class UpdateCartLine extends AbstractAction
{
    /**
     * Execute the action.
     */
    public function execute(
        int $cartLineId,
        int $quantity,
        $meta = null
    ): self {
        DB::transaction(function () use ($cartLineId, $quantity, $meta) {
            $data = [
                'quantity' => $quantity,
            ];

            if ($meta) {
                if (is_object($meta)) {
                    $meta = (array) $meta;
                }
                $data['meta'] = $meta;
            }

            CartLine::whereId($cartLineId)->update($data);
        });

        return $this;
    }
}
