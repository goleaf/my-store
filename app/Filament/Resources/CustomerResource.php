<?php

namespace App\Filament\Resources;

use App\Filament\Resources\Customer;
use App\Filament\Resources\CustomerResource\Pages;
use App\Filament\Resources\CustomerResource\RelationManagers\AddressRelationManager;
use App\Filament\Resources\CustomerResource\RelationManagers\OrdersRelationManager;
use App\Filament\Resources\CustomerResource\RelationManagers\UserRelationManager;
use App\Filament\Resources\CustomerResource\Widgets\CustomerStatsOverviewWidget;
use App\Models\Contracts\Customer as CustomerContract;
use App\Support\Resources\BaseResource;
use Filament\Forms;
use Filament\Forms\Components\Component;
use Filament\Support\Facades\FilamentIcon;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Filament\Actions;
use Filament\Schemas\Components as SchemaComponents;

class CustomerResource extends BaseResource
{
    protected static ?string $permission = 'sales:manage-customers';

    protected static ?string $model = CustomerContract::class;

    protected static ?int $navigationSort = 2;

    protected static int $globalSearchResultsLimit = 5;

    public static function getWidgets(): array
    {
        return [
            CustomerStatsOverviewWidget::class,
        ];
    }

    public static function getDefaultForm(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
    {
        return $schema
            ->components([
                SchemaComponents\Group::make([
                    SchemaComponents\Section::make()
                        ->id('details')
                        ->schema(
                            static::getMainFormComponents()
                        ),
                    static::getAttributeDataFormComponent(),
                ])->columnSpan(4),
                SchemaComponents\Section::make()
                    ->id('details')
                    ->schema(
                        static::getSideFormComponents()
                    )->columnSpan(2),
            ])->columns(6);
    }

    public static function getNavigationIcon(): ?string
    {
        return FilamentIcon::resolve('store::customers');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('admin::global.sections.sales');
    }

    public static function getLabel(): string
    {
        return __('admin::customer.label');
    }

    public static function getPluralLabel(): string
    {
        return __('admin::customer.plural_label');
    }

    protected static function getMainFormComponents(): array
    {
        return [
            SchemaComponents\Group::make()->schema([
                static::getTitleFormComponent()->columnSpan(1),
                static::getFirstNameFormComponent()->columnSpan(2),
                static::getLastNameFormComponent()->columnSpan(2),
            ])->columns(5),
            static::getCompanyNameFormComponent(),
            SchemaComponents\Group::make()->schema([
                static::getAccountRefFormComponent(),
                static::getTaxIdFormComponent(),
            ])->columns(2),
        ];
    }

    protected static function getSideFormComponents(): array
    {
        return [
            static::getCustomerGroupsFormComponent(),
        ];
    }

    protected static function getTitleFormComponent(): Component
    {
        return Forms\Components\TextInput::make('title')
            ->label(__('admin::customer.form.title.label'))
            ->required()
            ->maxLength(255)
            ->autofocus();
    }

    protected static function getAttributeDataFormComponent(): Component
    {
        return \App\Support\Forms\Components\Attributes::make();
    }

    protected static function getFirstNameFormComponent(): Component
    {
        return Forms\Components\TextInput::make('first_name')
            ->label(__('admin::customer.form.first_name.label'))
            ->required()
            ->maxLength(255)
            ->autofocus();
    }

    protected static function getLastNameFormComponent(): Component
    {
        return Forms\Components\TextInput::make('last_name')
            ->label(__('admin::customer.form.last_name.label'))
            ->required()
            ->maxLength(255)
            ->autofocus();
    }

    protected static function getCompanyNameFormComponent(): Component
    {
        return Forms\Components\TextInput::make('company_name')
            ->label(__('admin::customer.form.company_name.label'))
            ->nullable()
            ->maxLength(255)
            ->autofocus();
    }

    protected static function getAccountRefFormComponent(): Component
    {
        return Forms\Components\TextInput::make('account_ref')
            ->label(__('admin::customer.form.account_ref.label'))
            ->nullable()
            ->maxLength(255);
    }

    protected static function getTaxIdFormComponent(): Component
    {
        return Forms\Components\TextInput::make('tax_identifier')
            ->label(__('admin::customer.form.tax_identifier.label'))
            ->nullable()
            ->maxLength(255);
    }

    protected static function getCustomerGroupsFormComponent(): Component
    {
        return Forms\Components\CheckboxList::make('customerGroups')
            ->label(__('admin::customer.form.customer_groups.label'))
            ->relationship(
                name: 'customerGroups',
                titleAttribute: 'name',
                modifyQueryUsing: fn (Builder $query) => $query->distinct(
                    ['id', 'name', 'handle', 'default']
                )
            );
    }

    protected static function getDefaultTable(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('first_name')
                    ->label(__('admin::customer.table.first_name.label'))
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('last_name')
                    ->label(__('admin::customer.table.last_name.label'))
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('company_name')
                    ->label(__('admin::customer.table.company_name.label'))
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('tax_identifier')
                    ->label(__('admin::customer.table.tax_identifier.label'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('account_ref')
                    ->label(__('admin::customer.table.account_reference.label'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('customerGroups.name')
                    ->label(__('admin::customergroup.label'))
                    ->badge()
                    ->limitList(1)
                    ->tooltip(function (Tables\Columns\TextColumn $column, Model $record): ?string {
                        if ($record->customerGroups->count() <= $column->getListLimit()) {
                            return null;
                        }

                        return $record->customerGroups
                            ->map(fn ($customerGroup) => $customerGroup->name)
                            ->implode(', ');
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('customer_group')
                    ->label(__('admin::customergroup.label'))
                    ->relationship(
                        name: 'customerGroups',
                        titleAttribute: 'name',
                        modifyQueryUsing: fn (Builder $query) => $query->distinct(
                            ['id', 'name', 'handle', 'default']
                        )
                    )
                    ->multiple()
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->selectCurrentPageOnly();
    }

    public static function getDefaultRelations(): array
    {
        return [
            OrdersRelationManager::class,
            AddressRelationManager::class,
            UserRelationManager::class,
        ];
    }

    public static function getDefaultPages(): array
    {
        return [
            'index' => CustomerResource\Pages\ListCustomers::route('/'),
            'create' => CustomerResource\Pages\CreateCustomer::route('/create'),
            'edit' => CustomerResource\Pages\EditCustomer::route('/{record}/edit'),
            'view' => CustomerResource\Pages\ViewCustomer::route('/{record}'),
        ];
    }

    public static function getGlobalSearchResultTitle(Model $record): string|Htmlable
    {
        return $record->company_name ?: $record->fullName;
    }

    public static function getGloballySearchableAttributes(): array
    {
        return [
            'first_name',
            'last_name',
            'company_name',
            'account_ref',
            'tax_identifier',
            'users.name',
            'users.email',
        ];
    }

    public static function getGlobalSearchEloquentQuery(): Builder
    {
        return parent::getGlobalSearchEloquentQuery()->with([
            'users',
        ]);
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        /** @var Customer $record */
        $details = [
            __('admin::customer.table.full_name.label') => $record->fullName,
            __('admin::customer.table.title.label') => $record->title,
        ];

        if ($record->account_ref) {
            $details[__('admin::customer.table.account_reference.label')] = $record->account_ref;
        }

        if ($record->users() && $record->users()->count() >= 1) {
            $details[__('admin::user.table.email.label')] = $record->users()->first()->email;
        }

        return $details;
    }
}
