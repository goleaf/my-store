<?php

namespace App\Support\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \App\Filament\AdminPanelManager register()
 * @method static \App\Filament\AdminPanelManager panel(\Closure $closure)
 * @method static \Filament\Panel getPanel()
 * @method static \App\Filament\AdminPanelManager forceTwoFactorAuth(bool $state = true)
 * @method static \App\Filament\AdminPanelManager disableTwoFactorAuth()
 * @method static \App\Filament\AdminPanelManager extensions(array $extensions)
 * @method static array getExtensions()
 * @method static array getResources()
 * @method static array getPages()
 * @method static array getWidgets()
 * @method static \App\Filament\AdminPanelManager useRoleAsAdmin(array|string $roleHandle)
 * @method static mixed callHook(string $class, object|null $caller, string $hookName, void ...$args)
 *
 * @see \App\Filament\AdminPanelManager
 */
class AdminPanel extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'admin-panel';
    }
}
