<?php

namespace App\Traits;

use App\Models\ProductVariant;
use App\Facades\CartSession;
use Filament\Notifications\Notification;
use Exception;

trait CanAddToCart
{
    /**
     * Add a variant to the cart.
     */
    public function addToCart(int $variantId, int $quantity = 1): void
    {
        $variant = ProductVariant::find($variantId);

        if (! $variant) {
            Notification::make()
                ->title('Product not found.')
                ->danger()
                ->send();
            return;
        }

        try {
            CartSession::manager()->add($variant, $quantity);

            $this->dispatch('add-to-cart');
            $this->dispatch('cartUpdated');

            Notification::make()
                ->title('Added to cart')
                ->success()
                ->send();
        } catch (Exception $e) {
            Notification::make()
                ->title('Could not add to cart')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }
}
