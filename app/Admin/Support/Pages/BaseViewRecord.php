<?php

namespace App\Admin\Support\Pages;

use Filament\Resources\Pages\ViewRecord;

abstract class BaseViewRecord extends ViewRecord
{
    use Concerns\ExtendsFooterWidgets;
    use Concerns\ExtendsHeaderActions;
    use Concerns\ExtendsHeaderWidgets;
    use Concerns\ExtendsHeadings;
    use Concerns\ExtendsInfolist;
    use \App\Admin\Support\Concerns\CallsHooks;
}
