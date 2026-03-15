<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\Pages\ManageOrder;
use App\Store\Models\Contracts\Order as OrderContract;
use App\Store\Models\Order;
use App\Support\Actions\Orders\UpdateStatusBulkAction;
use App\Support\CustomerStatus;
use App\Support\OrderStatus;
use App\Support\Resources\BaseResource;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Support\Facades\FilamentIcon;
use Filament\Tables;
use Filament\Tables\Filters\Indicator;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Filament\Actions;

class OrderResource extends BaseResource
{
    protected static ?string $permission = 'sales:manage-orders';

    protected static ?string $model = OrderContract::class;

    protected static ?int $navigationSort = 1;

    protected static int $globalSearchResultsLimit = 5;

    public static function getLabel(): string
    {
        return __('admin::order.label');
    }

    public static function getPluralLabel(): string
    {
        return __('admin::order.plural_label');
    }

    public static function getNavigationIcon(): ?string
    {
        return FilamentIcon::resolve('store::orders');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('admin::global.sections.sales');
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::whereIn('status', config('store.panel.order_count_statuses', 'payment-received'))->count();
    }

    public static function getDefaultTable(Table $table): Table
    {
        return $table
            ->columns(static::getTableColumns())
            ->filters(static::getTableFilters())
            ->modifyQueryUsing(
                fn (Builder $query): Builder => $query->with(['currency'])
            )
            ->persistFiltersInSession()
            ->actions([
                Actions\EditAction::make()
                    ->url(fn ($record) => ManageOrder::getUrl(['record' => $record])),
            ])
            ->recordUrl(fn ($record) => ManageOrder::getUrl(['record' => $record]))
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    UpdateStatusBulkAction::make('update_status')
                        ->deselectRecordsAfterCompletion(),
                ]),
            ])
            ->defaultSort('id', 'DESC')
            ->selectCurrentPageOnly()
            ->deferLoading()
            ->poll('60s');
    }

    public static function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('status')
                ->label(__('admin::order.table.status.label'))
                ->toggleable()
                ->formatStateUsing(fn (string $state) => OrderStatus::getLabel($state))
                ->color(fn (string $state) => OrderStatus::getColor($state))
                ->badge(),
            Tables\Columns\TextColumn::make('reference')
                ->label(__('admin::order.table.reference.label'))
                ->toggleable()
                ->searchable(),
            Tables\Columns\TextColumn::make('customer_reference')
                ->label(__('admin::order.table.customer_reference.label'))
                ->toggleable()
                ->searchable(),
            Tables\Columns\TextColumn::make('billingAddress.fullName')
                ->label(__('admin::order.table.customer.label'))
                ->toggleable()
                ->searchable(['first_name', 'last_name']),
            Tables\Columns\TextColumn::make('new_customer')
                ->label(__('admin::order.table.new_customer.label'))
                ->toggleable()
                ->formatStateUsing(fn (bool $state) => CustomerStatus::getLabel($state))
                ->color(fn (bool $state) => CustomerStatus::getColor($state))
                ->icon(fn (bool $state) => CustomerStatus::getIcon($state))
                ->badge(),
            Tables\Columns\TextColumn::make('tags.value')
                ->label(__('admin::order.table.tags.label'))
                ->badge()
                ->toggleable()
                ->separator(','),
            Tables\Columns\TextColumn::make('billingAddress.postcode')
                ->label(__('admin::order.table.postcode.label'))
                ->toggleable()
                ->searchable(),
            Tables\Columns\TextColumn::make('billingAddress.contact_email')
                ->label(__('admin::order.table.email.label'))
                ->toggleable()
                ->copyable()
                ->copyMessage(__('admin::order.table.email.copy_message'))
                ->copyMessageDuration(1500)
                ->searchable(),
            Tables\Columns\TextColumn::make('billingAddress.contact_phone')
                ->label(__('admin::order.table.phone.label'))
                ->toggleable(),
            Tables\Columns\TextColumn::make('total')
                ->label(__('admin::order.table.total.label'))
                ->toggleable()
                ->formatStateUsing(fn ($state): string => $state->formatted),
            Tables\Columns\TextColumn::make('placed_at')
                ->label(__('admin::order.table.date.label'))
                ->toggleable()
                ->dateTime(),
        ];
    }

    public static function getTableFilters(): array
    {
        return [
            Tables\Filters\SelectFilter::make('status')
                ->label(__('admin::order.table.status.label'))
                ->options(collect(config('store.orders.statuses', []))
                    ->mapWithKeys(fn ($data, $status) => [$status => $data['label']]))
                ->multiple(),
            Tables\Filters\Filter::make('placed_at')

                ->form([
                    Forms\Components\DatePicker::make('placed_after')
                        ->label(__('admin::order.table.placed_after.label'))
                        ->default(Carbon::now()->subMonths(6)),
                    Forms\Components\DatePicker::make('placed_before')
                        ->label(__('admin::order.table.placed_before.label')),
                ])
                ->query(function (Builder $query, array $data): Builder {
                    return $query
                        ->when(
                            $data['placed_after'],
                            fn (Builder $query, $date): Builder => $query->whereDate('placed_at', '>=', $date),
                        )
                        ->when(
                            $data['placed_before'],
                            fn (Builder $query, $date): Builder => $query->whereDate('placed_at', '<=', $date),
                        );
                })
                ->indicateUsing(function (array $data): array {
                    $indicators = [];

                    if ($data['placed_after'] ?? null) {
                        $indicators[] = Indicator::make(__('admin::order.table.placed_after.label').' '.Carbon::parse($data['placed_after'])->toFormattedDateString())
                            ->removeField('placed_after');
                    }

                    if ($data['placed_before'] ?? null) {
                        $indicators[] = Indicator::make(__('admin::order.table.placed_before.label').' '.Carbon::parse($data['placed_before'])->toFormattedDateString())
                            ->removeField('placed_before');
                    }

                    return $indicators;
                }),
            Tables\Filters\SelectFilter::make('tags')
                ->label(__('admin::order.table.tags.label'))
                ->multiple()
                ->relationship('tags', 'value'),
        ];
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getDefaultPages(): array
    {
        return [
            'index' => OrderResource\Pages\ListOrders::route('/'),
            // 'create' => Pages\CreateOrder::route('/create'),
            'order' => OrderResource\Pages\ManageOrder::route('/{record}'),
            'edit' => OrderResource\Pages\EditOrder::route('/{record}/edit'),
        ];
    }

    public static function getGlobalSearchResultTitle(Model $record): string|Htmlable
    {
        return $record->reference;
    }

    public static function getGlobalSearchResultUrl(Model $record): ?string
    {
        return OrderResource::getUrl('order', [
            'record' => $record,
        ]);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return [
            'reference',
            'customer_reference',
            'notes',
            'billingAddress.first_name',
            'billingAddress.last_name',
            'billingAddress.contact_email',
            'tags.value',
        ];
    }

    public static function getGlobalSearchEloquentQuery(): Builder
    {
        return parent::getGlobalSearchEloquentQuery()->with([
            'billingAddress',
            'tags',
        ]);
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        /** @var Order $record */
        $details = [
            __('admin::order.table.status.label') => $record->getStatusLabelAttribute(),
            __('admin::order.table.total.label') => $record->total?->formatted,
            __('admin::order.table.customer.label') => $record->billingAddress?->fullName,
        ];

        if ($record->billingAddress?->contact_email) {
            $details[__('admin::order.table.email.label')] = $record->billingAddress->contact_email;
        }

        if ($record->placed_at) {
            $details[__('admin::order.table.date.label')] = $record->placed_at;
        }

        return $details;
    }
}
