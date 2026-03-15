<?php

namespace App\Support\RelationManagers;

use App\Events\ModelMediaUpdated;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Actions\Action;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Filament\Actions;

class MediaRelationManager extends BaseRelationManager
{
    protected static bool $isLazy = false;

    protected static string $relationship = 'media';

    public string $mediaCollection = 'default';

    public function isReadOnly(): bool
    {
        return false;
    }

    public function getDefaultForm(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\TextInput::make('custom_properties.name')
                    ->label(__('admin::relationmanagers.medias.form.name.label'))
                    ->maxLength(255),
                Forms\Components\Toggle::make('custom_properties.primary')
                    ->label(__('admin::relationmanagers.medias.form.primary.label'))
                    ->inline(false),
                Forms\Components\FileUpload::make('media')
                    ->label(__('admin::relationmanagers.medias.form.media.label'))
                    ->columnSpan(2)
                    ->hiddenOn('edit')
                    ->storeFiles(false)
                    ->imageEditor()
                    ->required()
                    ->imageEditorAspectRatios([
                        null,
                        '16:9',
                        '4:3',
                        '1:1',
                    ]),
            ]);
    }

    public function getDefaultTable(Table $table): Table
    {
        return $table
            ->heading(function () {
                return $this->getOwnerRecord()->getMediaCollectionTitle($this->mediaCollection) ?? Str::ucfirst($this->mediaCollection);
            })
            ->description(function () {
                return $this->getOwnerRecord()->getMediaCollectionDescription($this->mediaCollection) ?? '';
            })
            ->recordTitleAttribute('name')
            ->modifyQueryUsing(fn (Builder $query) => $query->where('collection_name', $this->mediaCollection)->orderBy('order_column'))
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->state(function (Media $record): string {
                        return $record->hasGeneratedConversion('small') ? $record->getUrl('small') : '';
                    })
                    ->label(__('admin::relationmanagers.medias.table.image.label')),
                Tables\Columns\TextColumn::make('file_name')
                    ->limit(30)
                    ->label(__('admin::relationmanagers.medias.table.file.label')),
                Tables\Columns\TextColumn::make('custom_properties.name')
                    ->label(__('admin::relationmanagers.medias.table.name.label')),
                Tables\Columns\IconColumn::make('custom_properties.primary')
                    ->label(__('admin::relationmanagers.medias.table.primary.label'))
                    ->boolean(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Actions\CreateAction::make()
                    ->label(__('admin::relationmanagers.medias.actions.create.label'))
                    ->using(function (array $data, string $model): Model {

                        return $this->getOwnerRecord()->addMediaFromString($data['media']->get())
                            ->usingFileName(
                                $data['media']->getClientOriginalName()
                            )
                            ->withCustomProperties([
                                'name' => $data['custom_properties']['name'],
                                'primary' => $data['custom_properties']['primary'],
                            ])
                            ->preservingOriginal()
                            ->toMediaCollection($this->mediaCollection);
                    })->after(
                        fn () => ModelMediaUpdated::dispatch(
                            $this->getOwnerRecord()
                        )
                    ),
            ])
            ->actions([
                Actions\EditAction::make()->after(
                    fn () => ModelMediaUpdated::dispatch(
                        $this->getOwnerRecord()
                    )
                ),
                Actions\DeleteAction::make(),
                Action::make('view_open')
                    ->label(__('admin::relationmanagers.medias.actions.view.label'))
                    ->icon('lucide-eye')
                    ->url(fn (Media $record): string => $record->getUrl())
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make()->after(
                        fn () => ModelMediaUpdated::dispatch(
                            $this->getOwnerRecord()
                        )
                    ),
                ]),
            ])
            ->reorderable('order_column');
    }
}
