<?php

namespace App\Support\Pages;

use Filament\Resources\Pages\ManageRelatedRecords;

abstract class BaseManageRelatedRecords extends ManageRelatedRecords
{
    use \App\Support\Pages\Concerns\ExtendsFooterWidgets;
    use \App\Support\Pages\Concerns\ExtendsHeaderActions;
    use \App\Support\Pages\Concerns\ExtendsHeaderWidgets;
    use \App\Support\Pages\Concerns\ExtendsHeadings;
    use \App\Support\Concerns\CallsHooks;
}
