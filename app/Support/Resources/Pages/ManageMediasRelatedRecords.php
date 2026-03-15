<?php

namespace App\Support\Resources\Pages;

use App\Support\Pages\BaseManageRelatedRecords;
use App\Support\RelationManagers\MediaRelationManager;
use Filament\Resources\RelationManagers\RelationGroup;
use Filament\Support\Facades\FilamentIcon;

class ManageMediasRelatedRecords extends BaseManageRelatedRecords
{
    protected static string $relationship = 'media';

    public function getTitle(): string
    {
        return __('admin::relationmanagers.medias.title_plural');
    }

    public static function getNavigationIcon(): ?string
    {
        return FilamentIcon::resolve('store::media');
    }

    public static function getNavigationLabel(): string
    {
        return __('admin::relationmanagers.medias.title_plural');
    }

    public function getRelationManagers(): array
    {
        $mediaCollections = $this->getOwnerRecord()->getRegisteredMediaCollections();

        $relationManagers = [];

        foreach ($mediaCollections as $mediaCollection) {
            $relationManagers[] = MediaRelationManager::make([
                'mediaCollection' => $mediaCollection->name,
            ]);
        }

        return [
            RelationGroup::make('Media', $relationManagers),
        ];
    }
}
