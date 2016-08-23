<?php
use Illuminate\Database\Seeder;
use App\Data\Blog\RolePermission;

class RolePermissionTableSeeder extends Seeder
{

    public function run()
    {
        $roleIds = [
            3 => 'admin',
        ];

        $permissionResources = [
            'tag' => [
                'read',
                'add',
                'edit',
                'delete',
            ],
            'privilege' => [
                'read',
                'add',
                'edit',
                'delete',
            ],
        ];

        foreach ($roleIds as $roleId => $roleTitle) {
            $permissionId = 1;
            foreach ($permissionResources as $permissionActions) {
                foreach ($permissionActions as $permissionAction) {
                    RolePermission::create([
                        'role_id' => $roleId,
                        'permission_id' => $permissionId,
                    ]);
                    $permissionId++;
                }
            }
        }
    }

}