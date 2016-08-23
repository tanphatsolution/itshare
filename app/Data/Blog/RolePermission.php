<?php namespace App\Data\Blog;

use Illuminate\Database\Eloquent\SoftDeletes;

class RolePermission extends BaseModel
{

    use SoftDeletes;

    // The database table used by the model.
    protected $table = 'role_permissions';

    // The attributes which prevents the listed columns from mass assignment
    protected $guarded = ['id'];

    // The attributes specifies which attributes should be mass-assignable
    protected $fillable = ['role_id', 'permission_id'];

    public static function updateRolePermission($roles = [], $inputRolePermissions = [])
    {
        if (empty($inputRolePermissions)) {
            RolePermission::whereNotNull('id')->delete();
            return;
        }
        foreach ($roles as $role) {
            $roleId = $role['id'];
            $permissions = $role['permission'];
            // Remove all current permission of Role if data empty or not isset
            if (!isset($inputRolePermissions[$roleId]) || empty($inputRolePermissions[$roleId])) {
                RolePermission::where('role_id', $roleId)
                    ->delete();
                continue;
            }
            // Check each currently permissions of Role
            foreach ($permissions as $permission) {
                $permissionId = $permission['id'];
                // Delete if not found in post data
                if (!self::isExisting($permissionId, $inputRolePermissions[$roleId])) {
                    RolePermission::where('role_id', $roleId)
                        ->where('permission_id', $permissionId)
                        ->delete();
                }
                unset($inputRolePermissions[$roleId][$permissionId]);
            }
        }
        // Insert all new setting permission
        foreach ($inputRolePermissions as $roleId => $permissions) {
            foreach ($permissions as $permissionId => $value) {
                RolePermission::create([
                    'role_id' => $roleId,
                    'permission_id' => $permissionId,
                ]);
            }
        }

        return;
    }

    public static function isExisting($permissionId, $inputRolePermissions)
    {
        if (empty($inputRolePermissions)) {
            return false;
        }
        foreach ($inputRolePermissions as $id => $value) {
            if ($permissionId == $id) {
                return true;
            }
        }
        return false;
    }

}