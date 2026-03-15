<?php

namespace App\Admin\Support\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \App\Admin\AdminPanelManager register()
 * @method static \App\Admin\AdminPanelManager panel(\Closure $closure)
 * @method static \Filament\Panel getPanel()
 * @method static \App\Admin\AdminPanelManager forceTwoFactorAuth(bool $state = true)
 * @method static \App\Admin\AdminPanelManager disableTwoFactorAuth()
 * @method static \App\Admin\AdminPanelManager extensions(array $extensions)
 * @method static array getExtensions()
 * @method static array getResources()
 * @method static array getPages()
 * @method static array getWidgets()
 * @method static \App\Admin\AdminPanelManager useRoleAsAdmin(array|string $roleHandle)
 * @method static mixed callHook(string $class, object|null $caller, string $hookName, void ...$args)
 *
 * @see \App\Admin\AdminPanelManager
 */
class AdminPanel extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'admin-panel';
    }
}
