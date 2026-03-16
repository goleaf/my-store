<?php

namespace App\Filament\Resources\CustomerResource\RelationManagers;

use App\Base\Enums\SavedPaymentMethodType;
use App\Models\SavedPaymentMethod;
use App\Support\RelationManagers\BaseRelationManager;
use Filament\Actions;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PaymentMethodsRelationManager extends BaseRelationManager
{
    protected static string $relationship = 'paymentMethods';

    public function isReadOnly(): bool
    {
        return false;
    }

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return 'Payment Methods';
    }

    public function getDefaultForm(Schema $schema): Schema
    {
        return $schema
            ->components([
                Components\Grid::make(2)->schema([
                    Select::make('type')
                        ->options(
                            collect(SavedPaymentMethodType::cases())
                                ->mapWithKeys(fn (SavedPaymentMethodType $type): array => [
                                    $type->value => $type->label(),
                                ])
                                ->all()
                        )
                        ->required()
                        ->live(),
                    Toggle::make('is_default')
                        ->label('Default payment method'),
                ]),
                Components\Grid::make(2)->schema([
                    TextInput::make('brand')
                        ->maxLength(50)
                        ->visible(fn (Get $get): bool => $get('type') === SavedPaymentMethodType::Card->value),
                    TextInput::make('last_four')
                        ->label('Last four digits')
                        ->minLength(4)
                        ->maxLength(4)
                        ->visible(fn (Get $get): bool => $get('type') === SavedPaymentMethodType::Card->value),
                    TextInput::make('expiry_month')
                        ->numeric()
                        ->minValue(1)
                        ->maxValue(12)
                        ->visible(fn (Get $get): bool => $get('type') === SavedPaymentMethodType::Card->value),
                    TextInput::make('expiry_year')
                        ->numeric()
                        ->minValue((int) now()->year)
                        ->maxValue((int) now()->year + 25)
                        ->visible(fn (Get $get): bool => $get('type') === SavedPaymentMethodType::Card->value),
                    TextInput::make('paypal_email')
                        ->email()
                        ->maxLength(255)
                        ->visible(fn (Get $get): bool => $get('type') === SavedPaymentMethodType::Paypal->value),
                    TextInput::make('payoneer_account_id')
                        ->maxLength(255)
                        ->visible(fn (Get $get): bool => $get('type') === SavedPaymentMethodType::Payoneer->value),
                ]),
            ]);
    }

    public function getDefaultTable(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('type')
            ->columns([
                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => Str::headline($state)),
                Tables\Columns\TextColumn::make('brand')
                    ->placeholder('N/A')
                    ->searchable(),
                Tables\Columns\TextColumn::make('last_four')
                    ->label('Last 4')
                    ->placeholder('N/A'),
                Tables\Columns\TextColumn::make('expiry_month')
                    ->label('Expiry')
                    ->formatStateUsing(fn (?int $state, SavedPaymentMethod $record): string => $state && $record->expiry_year
                        ? str_pad((string) $state, 2, '0', STR_PAD_LEFT) . '/' . $record->expiry_year
                        : 'N/A'),
                Tables\Columns\TextColumn::make('paypal_email')
                    ->label('PayPal')
                    ->placeholder('N/A')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('payoneer_account_id')
                    ->label('Payoneer')
                    ->placeholder('N/A')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\IconColumn::make('is_default')
                    ->boolean()
                    ->label('Default'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->headerActions([
                Actions\CreateAction::make()
                    ->mutateFormDataUsing(function (array $data): array {
                        return $this->sanitizeMethodData($data);
                    })
                    ->after(function (SavedPaymentMethod $record): void {
                        $this->syncDefaultMethod($record);
                    }),
            ])
            ->actions([
                Actions\EditAction::make()
                    ->mutateFormDataUsing(function (array $data): array {
                        return $this->sanitizeMethodData($data);
                    })
                    ->after(function (SavedPaymentMethod $record): void {
                        $this->syncDefaultMethod($record);
                    }),
                Actions\Action::make('makeDefault')
                    ->label('Make Default')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->hidden(fn (SavedPaymentMethod $record): bool => $record->is_default)
                    ->action(function (SavedPaymentMethod $record): void {
                        $record->forceFill(['is_default' => true])->save();
                        $this->syncDefaultMethod($record);
                    }),
                Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    protected function sanitizeMethodData(array $data): array
    {
        if (($data['type'] ?? null) !== SavedPaymentMethodType::Card->value) {
            $data['brand'] = null;
            $data['last_four'] = null;
            $data['expiry_month'] = null;
            $data['expiry_year'] = null;
        }

        if (($data['type'] ?? null) !== SavedPaymentMethodType::Paypal->value) {
            $data['paypal_email'] = null;
        }

        if (($data['type'] ?? null) !== SavedPaymentMethodType::Payoneer->value) {
            $data['payoneer_account_id'] = null;
        }

        if (! $this->ownerRecord->paymentMethods()->exists()) {
            $data['is_default'] = true;
        }

        return $data;
    }

    protected function syncDefaultMethod(SavedPaymentMethod $record): void
    {
        if (! $record->is_default) {
            return;
        }

        $this->ownerRecord->paymentMethods()
            ->whereKeyNot($record->id)
            ->update(['is_default' => false]);
    }
}
