<?php

namespace App\Filament\Resources\OrderResource\Concerns;

use App\Support\Infolists\Components\Transaction;
use Filament\Infolists;
use Filament\Schemas\Components;

trait DisplaysTransactions
{
    public static function getDefaultTransactionsRepeatableEntry(): Infolists\Components\RepeatableEntry
    {
        return Infolists\Components\RepeatableEntry::make('transactions')
            ->hiddenLabel()
            ->placeholder(__('admin::order.infolist.transactions.placeholder'))
            ->getStateUsing(fn ($record) => $record->transactions)
            ->contained(false)
            ->schema([
                Transaction::make('transactions'),
            ]);
    }

    public static function getTransactionsRepeatableEntry(): Infolists\Components\RepeatableEntry
    {
        return self::callStaticStoreHook('extendTransactionsRepeatableEntry', static::getDefaultTransactionsRepeatableEntry());
    }

    public static function getDefaultTransactionsInfolist(): Components\Component
    {
        return Components\Section::make('transactions')
            ->heading(__('admin::order.infolist.transactions.label'))
            ->compact()
            ->collapsed(fn ($state) => filled($state))
            ->collapsible(fn ($state) => filled($state))
            ->schema([
                static::getTransactionsRepeatableEntry(),
            ]);
    }

    public static function getTransactionsInfolist(): Components\Component
    {
        return self::callStaticStoreHook('extendTransactionsInfolist', static::getDefaultTransactionsInfolist());
    }
}
