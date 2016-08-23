<?php namespace App\Data\Blog;

use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends BaseModel
{
    // Define Roles
    const MEMBER = 1;
    const MODERATOR = 2;
    const ADMIN = 3;

    use SoftDeletes;

    // The database table used by the model.
    protected $table = 'roles';

    // The attributes which prevents the listed columns from mass assignment
    protected $guarded = ['id'];

    // The attributes specifies which attributes should be mass-assignable
    protected $fillable = ['title'];

    public function rolePermission()
    {
        return $this->hasMany('App\Data\Blog\RolePermission');
    }

    public function permission()
    {
        return $this->belongsToMany('App\Data\Blog\Permission', 'role_permissions', 'role_id', 'permission_id')
            ->where('role_permissions.deleted_at', null);
    }

    public static function getNameByType($roleId)
    {
        return Role::where('id', $roleId)->first()->title;
    }

    public static function getTypeByName($roleTitle)
    {
        return Role::where('title', $roleTitle)->first()->id;
    }

    public static function initRoles($roles = [], $permissions = [])
    {
        foreach ($roles as &$role) {
            $initPermissions = [];
            foreach ($permissions as $permission) {
                if (self::isExistingPermission($role, $permission)) {
                    $permission['checked'] = true;
                } else {
                    $permission['checked'] = false;
                }
                $initPermissions[$permission['id']] = $permission;
            }
            $role['permission'] = $initPermissions;
        }
        return $roles;
    }

    public static function isExistingPermission($role, $permission)
    {
        if (!isset($role['permission']) || empty($role['permission'])) {
            return false;
        }
        foreach ($role['permission'] as $rolePermission) {
            if ($rolePermission['id'] == $permission['id']) {
                return true;
            }
        }
        return false;
    }

}