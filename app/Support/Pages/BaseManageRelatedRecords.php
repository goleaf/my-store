<?php

namespace App\Support\Pages;

use App\Support\Pages\Concerns\ExtendsFooterWidgets;
use App\Support\Pages\Concerns\ExtendsHeaderActions;
use App\Support\Pages\Concerns\ExtendsHeaderWidgets;
use App\Support\Pages\Concerns\ExtendsHeadings;
use Filament\Resources\Pages\ManageRelatedRecords;
use App\Support\Concerns\CallsHooks;

abstract class BaseManageRelatedRecords extends ManageRelatedRecords
{
    use ExtendsFooterWidgets;
    use ExtendsHeaderActions;
    use ExtendsHeaderWidgets;
    use ExtendsHeadings;
    use CallsHooks;
}
