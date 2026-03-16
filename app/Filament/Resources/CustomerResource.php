<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerResource\Pages;
use App\Filament\Resources\CustomerResource\RelationManagers\AddressRelationManager;
use App\Filament\Resources\CustomerResource\RelationManagers\OrdersRelationManager;
use App\Filament\Resources\CustomerResource\RelationManagers\PaymentMethodsRelationManager;
use App\Filament\Resources\CustomerResource\Widgets\CustomerStatsOverviewWidget;
use App\Http\Requests\Filament\Sales\CustomerRequest;
use App\Models\Customer;
use App\Support\Forms\Components\Attributes;
use App\Support\Resources\BaseResource;
use Filament\Actions;
use Filament\Forms;
use Filament\Schemas\Components;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Schema;
use Filament\Support\Facades\FilamentIcon;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class CustomerResource extends BaseResource
{
    protected static ?string $permission = 'sales:manage-customers';

    protected static ?string $model = Customer::class;

    protected static ?int $navigationSort = 2;

    protected static int $globalSearchResultsLimit = 5;

    public static function getWidgets(): array
    {
        return [
            CustomerStatsOverviewWidget::class,
        ];
    }

    public static function getDefaultForm(Schema $schema): Schema
    {
        return $schema
            ->components([
                Components\Group::make([
                    Components\Section::make()
                        ->id('details')
                        ->schema(
                            static::getMainFormComponents()
                        ),
                    static::getAttributeDataFormComponent(),
                ])->columnSpan(4),
                Components\Section::make()
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
            Components\Group::make()->schema([
                static::getTitleFormComponent()->columnSpan(1),
                static::getFirstNameFormComponent()->columnSpan(2),
                static::getLastNameFormComponent()->columnSpan(2),
            ])->columns(5),
            static::getCompanyNameFormComponent(),
            Components\Group::make()->schema([
                static::getEmailFormComponent(),
                static::getPhoneFormComponent(),
            ])->columns(2),
            Components\Group::make()->schema([
                static::getAccountRefFormComponent(),
                static::getTaxIdFormComponent(),
                static::getStatusFormComponent(),
                static::getLocaleFormComponent(),
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
        $request = static::request();

        return Forms\Components\TextInput::make('title')
            ->label(__('admin::customer.form.title.label'))
            ->rules($request->fieldRules('title'))
            ->required($request->fieldHasRule('title', 'required'))
            ->autofocus();
    }

    protected static function getAttributeDataFormComponent(): Component
    {
        return Attributes::make();
    }

    protected static function getFirstNameFormComponent(): Component
    {
        $request = static::request();

        return Forms\Components\TextInput::make('first_name')
            ->label(__('admin::customer.form.first_name.label'))
            ->rules($request->fieldRules('first_name'))
            ->required($request->fieldHasRule('first_name', 'required'))
            ->autofocus();
    }

    protected static function getLastNameFormComponent(): Component
    {
        $request = static::request();

        return Forms\Components\TextInput::make('last_name')
            ->label(__('admin::customer.form.last_name.label'))
            ->rules($request->fieldRules('last_name'))
            ->required($request->fieldHasRule('last_name', 'required'))
            ->autofocus();
    }

    protected static function getCompanyNameFormComponent(): Component
    {
        $request = static::request();

        return Forms\Components\TextInput::make('company_name')
            ->label(__('admin::customer.form.company_name.label'))
            ->rules($request->fieldRules('company_name'))
            ->required($request->fieldHasRule('company_name', 'required'))
            ->autofocus();
    }

    protected static function getEmailFormComponent(): Component
    {
        return Forms\Components\TextInput::make('email')
            ->label('Email')
            ->type('email')
            ->rules(fn (?Model $record): array => static::request($record)->fieldRules('email'))
            ->required(fn (?Model $record): bool => static::request($record)->fieldHasRule('email', 'required'));
    }

    protected static function getPhoneFormComponent(): Component
    {
        $request = static::request();

        return Forms\Components\TextInput::make('phone')
            ->label('Phone')
            ->type('tel')
            ->rules($request->fieldRules('phone'))
            ->required($request->fieldHasRule('phone', 'required'));
    }

    protected static function getAccountRefFormComponent(): Component
    {
        $request = static::request();

        return Forms\Components\TextInput::make('account_ref')
            ->label(__('admin::customer.form.account_ref.label'))
            ->rules($request->fieldRules('account_ref'))
            ->required($request->fieldHasRule('account_ref', 'required'));
    }

    protected static function getTaxIdFormComponent(): Component
    {
        $request = static::request();

        return Forms\Components\TextInput::make('tax_identifier')
            ->label(__('admin::customer.form.tax_identifier.label'))
            ->rules($request->fieldRules('tax_identifier'))
            ->required($request->fieldHasRule('tax_identifier', 'required'));
    }

    protected static function getStatusFormComponent(): Component
    {
        $request = static::request();

        return Forms\Components\Select::make('status')
            ->label('Status')
            ->options([
                'active' => 'Active',
                'banned' => 'Banned',
                'unverified' => 'Unverified',
            ])
            ->default('active')
            ->rules($request->fieldRules('status'))
            ->required($request->fieldHasRule('status', 'required'));
    }

    protected static function getLocaleFormComponent(): Component
    {
        $request = static::request();

        return Forms\Components\TextInput::make('locale')
            ->label('Locale')
            ->default(config('app.locale'))
            ->rules($request->fieldRules('locale'))
            ->required($request->fieldHasRule('locale', 'required'));
    }

    protected static function getCustomerGroupsFormComponent(): Component
    {
        $request = static::request();

        return Forms\Components\CheckboxList::make('customerGroups')
            ->label(__('admin::customer.form.customer_groups.label'))
            ->rules($request->fieldRules('customerGroups'))
            ->required($request->fieldHasRule('customerGroups', 'required'))
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
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label('Phone')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('tax_identifier')
                    ->label(__('admin::customer.table.tax_identifier.label'))
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'success' => 'active',
                        'danger' => 'banned',
                        'warning' => 'unverified',
                    ]),
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
            PaymentMethodsRelationManager::class,
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
            'email',
            'phone',
            'account_ref',
            'tax_identifier',
        ];
    }

    public static function getGlobalSearchEloquentQuery(): Builder
    {
        return parent::getGlobalSearchEloquentQuery();
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        /** @var \App\Filament\Resources\Customer $record */
        $details = [
            __('admin::customer.table.full_name.label') => $record->fullName,
            __('admin::customer.table.title.label') => $record->title,
        ];

        if ($record->account_ref) {
            $details[__('admin::customer.table.account_reference.label')] = $record->account_ref;
        }

        if ($record->email) {
            $details['Email'] = $record->email;
        }

        if ($record->phone) {
            $details['Phone'] = $record->phone;
        }

        return $details;
    }

    protected static function request(?Model $record = null): CustomerRequest
    {
        return (new CustomerRequest)->forRecord($record);
    }
}
