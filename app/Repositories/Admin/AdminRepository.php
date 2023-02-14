<?php

namespace App\Repositories\Admin;

use App\Repositories\Admin\AdminRepositoryInterface;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;

class AdminRepository implements AdminRepositoryInterface
{
    public $connection;

    public function  __construct(Admin $admin)
    {
        $this->connection = $admin;
    }

    public function getTotalCount()
    {
        return $this->connection->count();
    }

    public function getAllAdmins()
    {
        return $this->connection->all();
    }

    public function findAdminById($id)
    {
        return $this->connection->find($id);
    }

    public function createAdmin($data)
    {
        $this->connection->name = $data->name;
        $this->connection->username = $data->username;
        $this->connection->email = $data->email;
        $this->connection->password = Hash::make($data->password);
       $response  =  $this->connection->save();

        if ($data->roles) {
            $this->connection->assignRole($data->roles);
        }
        return $response;
    }

    public function updateAdmin($admin, $data)
    {
       $res = $admin->save();

        $admin->roles()->detach();
        if ($data->roles) {
            $admin->assignRole($data->roles);
        }
        return $res;
    }

    public function deleteAdmin($id)
    {
        $admin = $this->findAdminById($id);
        if (!is_null($admin)) {
           return $admin->delete();
        }
    }
}
