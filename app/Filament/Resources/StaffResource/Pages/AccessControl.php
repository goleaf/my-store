<?php

namespace App\Filament\Resources\StaffResource\Pages;

use App\Filament\Resources\StaffResource;
use App\Support\Facades\AdminAccessControl;
use App\Support\Facades\AdminPanel;
use Exception;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Filament\Support\Enums\Size;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

/**
 * @property Collection $roles
 * @property Collection $permissions
 * @property Collection $groupedPermissions
 */
class AccessControl extends Page
{
    protected static string $resource = StaffResource::class;

    protected string $view = 'admin::resources.staff-resource.pages.access-control';

    public array $state = [];

    private string $stateSeparator = '#';

    public function getTitle(): string|Htmlable
    {
        return __('admin::staff.acl.title');
    }

    public function mount()
    {
        foreach ($this->roles as $role) {
            /** @var Role $roleModel */
            $roleModel = Role::findByName($role->handle, AdminPanel::getPanel()->getAuthGuard());

            $this->syncRolePermissions($roleModel);
        }
    }

    protected function syncRolePermissions(Model $role): void
    {
        $rolePermissions = $role->getAllPermissions()->pluck('name');

        foreach ($this->permissions as $permission) {
            data_set($this, $this->getStatePath($role->name, $permission->handle), $rolePermissions->contains($permission->handle));
        }
    }

    public function getStatePath(string $role, string $permission): string
    {
        return "state.{$role}{$this->stateSeparator}{$permission}";
    }

    public function updatedState($value, $path)
    {
        $result = $this->resolveRolePermissionFromPath($path);

        if (is_string($result)) {
            $error = $result;

            data_set($this, 'state.'.$path, false);

            Notification::make()
                ->title($error)
                ->danger()
                ->send();

            return;
        }

        [$role, $permission] = $result;
        /**
         * @var Role $role
         * @var Permission $permission
         * */
        if ($role && $permission) {
            if ($value == true) {
                $role->givePermissionTo($permission);
            } else {
                $role->revokePermissionTo($permission);

                $grouped = $this->groupedPermissions->first(fn ($gp) => $gp->handle == $permission->name);

                if (filled($grouped)) {
                    $role->revokePermissionTo($grouped->children->pluck('handle')->toArray());

                    foreach ($grouped->children->pluck('handle') as $perm) {
                        data_set($this, $this->getStatePath($role->name, $perm), false);
                    }
                }
            }

            Notification::make()
                ->title(__('admin::staff.acl.notification.updated'))
                ->success()
                ->send();
        }
    }

    private function resolveRolePermissionFromPath($path): string|array
    {
        $error = null;

        try {
            [$roleHandle, $permissionHandle] = explode($this->stateSeparator, $path);
        } catch (Exception $e) {
            $error = __('admin::staff.acl.notification.error');
        }

        if (blank($error)) {
            $registeredRole = $this->roles->first(fn ($r) => $roleHandle == $r->handle);
            $registeredPermission = $this->permissions->first(fn ($p) => $permissionHandle == $p->handle);

            try {
                $role = Role::findByName($roleHandle, AdminPanel::getPanel()->getAuthGuard());
            } catch (Exception $e) {
                $role = null;
            }

            try {
                $permission = Permission::findByName($permissionHandle, AdminPanel::getPanel()->getAuthGuard());
            } catch (Exception $e) {
                $permission = null;
            }

            if (blank($registeredRole) || blank($role)) {
                $error = __('admin::staff.acl.notification.no-role');
            }

            if (blank($registeredPermission) || blank($permission)) {
                $error = blank($error) ? __('admin::staff.acl.notification.no-permission') : __('admin::staff.acl.notification.no-role-permission');
            }
        }

        if (filled($error)) {
            return $error;
        } else {
            return [$role, $permission];
        }
    }

    public function getRolesProperty(): Collection
    {
        $admin = AdminAccessControl::getAdmin();

        return AdminAccessControl::getRoles()->reject(fn ($role) => $admin->contains($role->handle));
    }

    public function getPermissionsProperty(): Collection
    {
        return AdminAccessControl::getPermissions();
    }

    public function getGroupedPermissionsProperty(): Collection
    {
        return AdminAccessControl::getGroupedPermissions();
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('add_role')
                ->label('admin::staff.action.add-role.label')
                ->translateLabel()
                ->form([
                    TextInput::make('name')
                        ->label('admin::staff.form.role.label')
                        ->translateLabel()
                        ->unique(table: Role::class)
                        ->required(),
                ])
                ->action(fn ($data) => $this->syncRolePermissions(Role::create([
                    'name' => $data['name'],
                    'guard_name' => AdminPanel::getPanel()->getAuthGuard(),
                ])))
                ->modalWidth('md'),
        ];
    }

    public function deleteRoleAction(): Action
    {
        return Action::make('deleteRole')
            ->icon('heroicon-m-trash')
            ->color('danger')
            ->size(Size::Small)
            ->tooltip(__('admin::staff.action.delete-role.label'))
            ->iconButton()
            ->requiresConfirmation()
            ->modalHeading(function (Page $livewire) {
                $arguments = Arr::last($livewire->mountedActionsArguments);

                if ($handle = $arguments['handle'] ?? null) {
                    $role = AdminAccessControl::getRoles()->first(fn ($r) => $r->handle == $handle);

                    return __('admin::staff.action.delete-role.heading', ['role' => $role->transLabel]);
                }

                return null;
            })
            ->action(function ($arguments, Page $livewire) {
                $role = Role::findByName($arguments['handle']);
                $permissions = $role->getAllPermissions();

                foreach ($permissions as $permission) {
                    data_forget($livewire, $this->getStatePath($role->name, $permission->name));
                }

                $role->delete();
            });
    }
}
