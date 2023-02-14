<?php

namespace App\Repositories\Role;

use App\Repositories\Role\RoleRepositoryInterface;
use Spatie\Permission\Models\Role;

class RoleRepository implements RoleRepositoryInterface
{
    public $connection;

    public function  __construct(Role $role)
    {
        $this->connection = $role;
    }

    public function getTotalCount()
    {
        return $this->connection->count();
    }

    public function getAllRoles()
    {
        return $this->connection->all();
    }

    public function createRole($data, $permissions)
    {
        $role =  $this->connection->create($data);
        if (!empty($permissions)) {
            $role->syncPermissions($permissions);
        }
        return $role;
    }

    public function findAdminRoleById($id)
    {
        return $this->connection->findById($id, 'admin');
    }

    public function updateRole($role_name, $permissions, $id)
    {
       $role =  $this->findAdminRoleById($id);
        if (!empty($permissions)) {
            $role->name = $role_name;
            $role->save();
            $role->syncPermissions($permissions);
        }
        return $role;
    }

    public function deleteRole($id)
    {
        $role =  $this->findAdminRoleById($id);
        if (!is_null($role)) {
           return $role->delete();
        }
        return false;
    }
}
