<?php
namespace App\Services;

namespace App\Services;

use App\Data\Blog\Permission;
use App\Data\Blog\RolePermission;
use App\Data\Blog\UserRole;
use Illuminate\Support\Facades\Auth;

/**
 * Authority
 */
class AuthorityService
{
    /**
     * @var mixed Current user in the application for permissions to apply to
     */
    protected $currentUser;

    /**
     * Authority constructor
     * @param mixed $currentUser
     */
    public function __construct($currentUser = null)
    {
        if ($currentUser == null) {
            $currentUser = Auth::check() ? Auth::user() : null;
        }

        $this->setCurrentUser($currentUser);
    }

    /**
     * Returns current user - alias of getCurrentUser()
     *
     * @return mixed
     */
    public function user()
    {
        return $this->getCurrentUser();
    }

    /**
     * Set current user
     *
     * @return mixed
     */
    public function setCurrentUser($currentUser)
    {
        $this->currentUser = $currentUser;
    }

    /**
     * Returns current user
     *
     * @return mixed
     */
    public function getCurrentUser()
    {
        return $this->currentUser;
    }

    /**
     * Determine if current user can access the given resource and action
     * @param array $actions
     * @param string $resource
     * @return boolean
     */
    public function can($actions = null, $resource = null)
    {
        if (is_null($this->currentUser)) {
            return false;
        }

        if (is_null($actions) || is_null($resource) || ($resource == "''")) {
            return true;
        }

        $rolePermissions = RolePermission::where('role_id', $this->currentUser->roles->first()->id)->lists('permission_id')->toArray();

        $permissionIds = $this->getGivenPermissions($actions, $resource);

        if (empty($permissionIds)) {
            return false;
        }

        foreach ($permissionIds as $value) {
            if (!in_array($value, $rolePermissions)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Determine if current user cannot access the given resource and action
     * @param array $actions
     * @param string $resource
     * @return boolean
     */
    public function cannot($actions = null, $resource = null)
    {
        return !$this->can($actions, $resource);
    }

    /**
     * Determine role of current user
     * @return boolean
     */
    public function hasRole($roleName = '')
    {
        return isset($this->currentUser->roles()->first()->title) && $this->currentUser->roles()->first()->title == $roleName;
    }

    /**
     * Returns the current permissions set
     *
     * @return RuleRepository
     */
    public function getGivenPermissions($actions, $resource)
    {
        if (is_string($actions)) {
            $permissionId = Permission::where('resource', $resource)
                ->where('action', $actions)
                ->first()
                ->id;
            $permissionIds = is_null($permissionId) ? [] : [$permissionId];
            return $permissionIds;
        } else {
            if (is_array($actions)) {
                $permissionIds = Permission::where('resource', $resource)
                    ->whereIn('action', $actions)
                    ->get()
                    ->lists('id');
                $permissionIds = (count($permissionIds) == count($actions)) ? $permissionIds : [];
                return $permissionIds;
            }
        }

        return [];
    }

    /**
     * Determine role of current user
     * @return boolean
     */
    public function hasRoleByUser($user = null, $roleName = null)
    {
        if (is_null($user)) {
            $user = $this->currentUser;
        }
        if (is_null($roleName)) {
            return false;
        }
        $userId = $user->id;
        $userRoleName = UserRole::with('Role')
            ->where('user_id', $userId)
            ->first();
        if (is_null($userRoleName)) {
            return false;
        }
        $userRoleName = $userRoleName->role->title;
        return ($roleName === $userRoleName);
    }

    public function check()
    {
        return !empty($this->currentUser);
    }
}