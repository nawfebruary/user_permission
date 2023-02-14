<?php

namespace App\Repositories\Permission;

interface PermissionRepositoryInterface
{
    public function getTotalCount();

    public function getAllPermissions();
}
