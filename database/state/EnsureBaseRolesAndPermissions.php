<?php

namespace App\Admin\Database\State;

use App\Support\Facades\AdminAccessControl;
use App\Support\Facades\AdminPanel;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class EnsureBaseRolesAndPermissions
{
    public function prepare()
    {
        //
    }

    public function run()
    {
        $guard = AdminPanel::getPanel()->getAuthGuard();

        $tableNames = config('permission.table_names');

        if (Schema::hasTable($tableNames['roles'])) {
            foreach (AdminAccessControl::getBaseRoles() as $role) {
                Role::query()->firstOrCreate([
                    'name' => $role,
                    'guard_name' => $guard,
                ]);
            }
        }

        if (Schema::hasTable($tableNames['permissions'])) {
            // Rename any existing permissions
            Permission::where('name', 'catalogue:manage-products')->update(['name' => 'catalog:manage-products']);
            Permission::where('name', 'catalogue:manage-collections')->update(['name' => 'catalog:manage-collections']);
            Permission::where('name', 'catalogue:manage-orders')->update(['name' => 'sales:manage-orders']);
            Permission::where('name', 'catalogue:manage-customers')->update(['name' => 'sales:manage-customers']);
            Permission::where('name', 'catalogue:manage-discounts')->update(['name' => 'sales:manage-discounts']);

            foreach (AdminAccessControl::getBasePermissions() as $permission) {
                Permission::firstOrCreate([
                    'name' => $permission,
                    'guard_name' => $guard,
                ]);
            }
        }
    }
}
