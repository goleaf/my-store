<?php

namespace App\Filament\Extensions;

use App\Filament\Resources\ProductResource\Widgets\ProductOptionsWidget;
use App\Support\Extending\BaseExtension;

class ProductVariantsHeaderWidgetsExtension extends BaseExtension
{
    /**
     * Use app widget so variant form saves SKU (second field) and price via app trait.
     *
     * @param  array<int, class-string>  $widgets
     * @return array<int, class-string>
     */
    public function headerWidgets(array $widgets): array
    {
        return [
            ProductOptionsWidget::class,
        ];
    }
}
