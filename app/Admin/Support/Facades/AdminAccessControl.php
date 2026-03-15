<?php

namespace App\Admin\Support\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Illuminate\Support\Collection getRoles(bool $refresh = false)
 * @method static \Illuminate\Support\Collection getPermissions(bool $refresh = false)
 * @method static \Illuminate\Support\Collection getGroupedPermissions(bool $refresh = false)
 * @method static array getBaseRoles()
 * @method static array getBasePermissions()
 * @method static void useRoleAsAdmin(array|string $roleHandle)
 * @method static \Illuminate\Support\Collection getAdmin()
 * @method static \Illuminate\Support\Collection getRolesWithoutAdmin(bool $refresh = false)
 *
 * @see \App\Admin\Auth\Manifest
 */
class AdminAccessControl extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'admin-access-control';
    }
}
