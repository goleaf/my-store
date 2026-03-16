<?php

namespace App\Traits;

use App\Models\Wishlist;
use Filament\Notifications\Notification;

trait CanManageWishlist
{
    public function toggleWishlist(int $productId, ?int $variantId = null): void
    {
        if (! auth()->check()) {
            $this->redirect(route('login'), navigate: true);

            return;
        }

        $attributes = [
            'customer_id' => auth()->id(),
            'product_id' => $productId,
            'variant_id' => $variantId,
        ];

        $wishlistItem = Wishlist::query()->where($attributes)->first();

        if ($wishlistItem) {
            $wishlistItem->delete();

            Notification::make()
                ->title('Removed from wishlist')
                ->success()
                ->send();
        } else {
            Wishlist::query()->create($attributes);

            Notification::make()
                ->title('Added to wishlist')
                ->success()
                ->send();
        }

        $this->dispatch('wishlistUpdated');
    }

    public function getWishlistProductIdsProperty(): array
    {
        if (! auth()->check()) {
            return [];
        }

        return Wishlist::query()
            ->where('customer_id', auth()->id())
            ->pluck('product_id')
            ->all();
    }
}
