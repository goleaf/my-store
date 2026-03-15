<?php

use App\Filament\Extensions\ProductVariantsHeaderWidgetsExtension;
use App\Filament\Resources\ProductResource\Widgets\ProductOptionsWidget;

test('headerWidgets returns app ProductOptionsWidget', function () {
    $extension = new ProductVariantsHeaderWidgetsExtension;
    $widgets = $extension->headerWidgets([]);

    expect($widgets)->toBeArray()
        ->toContain(ProductOptionsWidget::class);
});
