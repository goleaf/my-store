<?php

namespace App\Support\Pages;

use App\Support\Pages\Concerns\ExtendsFooterWidgets;
use App\Support\Pages\Concerns\ExtendsHeaderActions;
use App\Support\Pages\Concerns\ExtendsHeaderWidgets;
use App\Support\Pages\Concerns\ExtendsHeadings;
use App\Support\Pages\Concerns\ExtendsInfolist;
use Filament\Resources\Pages\ViewRecord;
use App\Support\Concerns\CallsHooks;

abstract class BaseViewRecord extends ViewRecord
{
    use ExtendsFooterWidgets;
    use ExtendsHeaderActions;
    use ExtendsHeaderWidgets;
    use ExtendsHeadings;
    use ExtendsInfolist;
    use CallsHooks;
}
