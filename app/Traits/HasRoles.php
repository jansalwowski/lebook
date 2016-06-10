<?php


namespace App\Traits;


use App\Models\Role;
use App\Models\Permission;

/**
 * Class HasRoles
 * @package App\Traits
 */
trait HasRoles {


    /**
     * Get Roles of a User
     *
     * @return mixed
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * Check if User has Role or Roles
     *
     * @param $role
     * @return bool
     */
    public function hasRole($role)
    {
        if(is_string($role)){
            return $this->roles->contains('name', $role);
        }


        return !! $role->intersect($this->roles)->count();
    }

    /**
     * Add Role to a User
     *
     * @param $role
     * @return mixed
     */
    public function addRole($role)
    {
        if(is_string($role)){
            $role = Role::where('name', $role)->firstOrFail();
        }

        return $this->roles()->save($role);
    }

    /**
     * Remove Role of a User
     *
     * @param $role
     * @return mixed
     */
    public function removeRole($role)
    {
        if(is_string($role)){
            $role = Role::where('name', $role)->firstOrFail();
        }
        return $this->roles()->detach($role);
    }

    /**
     * Get Permissions of a User
     *
     * @return mixed
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }

    /**
     * Check if User has Permission
     *
     * @param $permission int|Permission
     * @return bool
     */
    public function hasPermission($permission)
    {
        if(is_string($permission)){
            return $this->roles->contains('name', $permission);
        }


        return !! $permission->intersect($this->permissions)->count();
    }

    /**
     * Grant User a Permission
     *
     * @param $permission int|Permission
     * @return mixed
     */
    public function grantPermission($permission)
    {
        if(is_string($permission)){
            $permission = Permission::where('name', $permission)->firstOrFail();
        }

        return $this->permissions()->save($permission);
    }

    /**
     * Alias for grantPermission() method. Grant User a Permission
     *
     * @param $permission int|Permission
     * @return mixed
     */
    public function addPermission($permission)
    {
        return $this->grantPermission($permission);
    }

    /**
     * Remove Permission from a User
     *
     * @param $permission int|Permission
     * @return mixed
     */
    public function removePermission($permission)
    {
        if(is_string($permission)){
            $permission = Permission::where('name', $permission)->firstOrFail();
        }

        return $this->permissions()->detach($permission);
    }
}