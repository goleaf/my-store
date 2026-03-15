<?php

namespace App\Support\Pages;

use Filament\Resources\Pages\ViewRecord;

abstract class BaseViewRecord extends ViewRecord
{
    use \App\Support\Pages\Concerns\ExtendsFooterWidgets;
    use \App\Support\Pages\Concerns\ExtendsHeaderActions;
    use \App\Support\Pages\Concerns\ExtendsHeaderWidgets;
    use \App\Support\Pages\Concerns\ExtendsHeadings;
    use \App\Support\Pages\Concerns\ExtendsInfolist;
    use \App\Support\Concerns\CallsHooks;
}
