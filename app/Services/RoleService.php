<?php
namespace App\Services;

use App\Data\Blog\Permission;
use App\Data\Blog\Role;
use App\Data\Blog\UserRole;

class RoleService
{

    public static function privilege()
    {
        // Get all permissions
        $permissions = Permission::orderBy('resource')
            ->orderBy('id')
            ->get()
            ->toArray();
        $permissionsGroup = Permission::getGroup($permissions);

        // Get and init roles
        $roles = Role::with('permission')->get()->toArray();
        $roles = Role::initRoles($roles, $permissions);

        $numberOfRoles = count($roles);

        return compact('permissions', 'permissionsGroup', 'roles', 'numberOfRoles');
    }

    public static function getAllRoles()
    {
        return [
            Role::MEMBER => trans('messages.role.member'),
            Role::MODERATOR => trans('messages.role.moderator'),
            Role::ADMIN => trans('messages.role.admin'),
        ];
    }

    public static function change($input)
    {
        if (!$input['user_id'] || !$input['role_id']) {
            return [
                'message' => trans('messages.role.not_change'),
                'type' => 'alert-danger',
            ];
        }
        $response = [
            'message' => trans('messages.role.change_fail'),
            'type' => 'alert-warning',
        ];
        $admin_count = UserRole::where('user_id', '<>', $input['user_id'])
            ->where('role_id', Role::ADMIN)
            ->count();
        if (!$admin_count) {
            return $response;
        }
        $userRole = UserRole::where('user_id', $input['user_id'])->first();
        $userRole->update($input);
        $response = [
            'message' => trans('messages.role.change_success'),
            'type' => 'alert-success',
        ];
        return $response;
    }
}