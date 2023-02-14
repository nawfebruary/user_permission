<?php

namespace App\Repositories\Role;

interface RoleRepositoryInterface
{
    public function getTotalCount();

    public function getAllRoles();

    public function createRole($data, $permissions);

    public function findAdminRoleById($id);

    public function updateRole($role_name, $permissions, $id);
}
