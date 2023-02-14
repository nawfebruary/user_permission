<?php

namespace App\Repositories\Admin;

use App\Models\Admin;

interface AdminRepositoryInterface
{
    public function getTotalCount();

    public function getAllAdmins();

    public function findAdminById($id);

    public function createAdmin($data);

    public function updateAdmin($admin, $data);

    public function deleteAdmin($id);
}
