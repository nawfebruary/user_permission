<?php

namespace App\Repositories\Permission;

use App\Repositories\Permission\PermissionRepositoryInterface;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionRepository implements PermissionRepositoryInterface
{
    public $connection;

    public function __construct(Permission $permission)
    {
        $this->connection = $permission;
    }

    public function getTotalCount()
    {
        return $this->connection->count();
    }

    public function getAllPermissions()
    {
        return $this->connection->all();
    }
}
