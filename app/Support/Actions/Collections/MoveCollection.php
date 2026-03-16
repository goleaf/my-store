<?php

namespace App\Support\Actions\Collections;

use App\Facades\DB;
use App\Models\Collection;
use Filament\Actions\Action;
use Illuminate\Database\Eloquent\Model;

class MoveCollection extends Action
{
    public function setUp(): void
    {
        parent::setUp();

        $this->record(function (array $arguments) {
            return Collection::find($arguments['id']);
        });

        $this->action(function (array $arguments, array $data, Model $record): void {
            DB::beginTransaction();

            $target = Collection::find($data['target_id']);

            $record->parent()->associate($target)->save();

            DB::commit();

            $this->success();

        });

        $this->label(
            __('admin::actions.collections.move.label')
        );
    }
}
