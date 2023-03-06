<?php

namespace App\Services;

use App\Models\Admin;
use App\Models\User;
use App\Repositories\Admin\AdminRepositoryInterface;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use DataTables;
class AdminService
{

    private $adminRepo;

    public function __construct(AdminRepositoryInterface $adminRepo)
    {
        $this->adminRepo = $adminRepo;
    }

    public function createDatatable($user)
    {
        $roles = $this->adminRepo->getAllAdmins();
        return DataTables::of($roles)->addIndexColumn()
            ->addColumn('roles', function($row){
                $btn = '';
                foreach ($row->roles as $perm) {
                    $btn.= '<span class="badge badge-info mr-1">'
                                .$perm->name.'
                            </span>';
                }
                return $btn;
            })
            ->addColumn('action', function($row) use ($user){
                $actionBtn = '';
                if($user->can('admin.edit'))
                    $actionBtn = '<a href="'.route('admin.admins.edit', $row->id) .'" class="btn btn-success btn-sm text-white mr-1">Edit</a>';
                if($user->can('admin.delete'))
                    $actionBtn.= '<a class="btn btn-danger btn-sm text-white" href="'.route('admin.admins.destroy', $row->id) .'"
                                        onclick="event.preventDefault(); document.getElementById(\'delete-form-'. $row->id.'\').submit();">
                                        Delete
                                    </a>
                                    <form id="delete-form-"'. $row->id .'" action="'.route('admin.admins.destroy', $row->id) .'" method="POST" style="display: none;">
                                           @method(\'DELETE\')
                                         @csrf</form>';
                return $actionBtn;
            })
            ->rawColumns(['roles', 'action'])
            ->make(true);
    }

    public function createAdmin($data)
    {
        return $this->adminRepo->createAdmin($data);
    }

    public function updateAdmin($id, $data)
    {
        $admin = $this->adminRepo->findAdminById($id);
        $admin->name = $data->name;
        $admin->email = $data->email;
        $admin->username = $data->username;
        if ($data->password) {
            $admin->password = Hash::make($data->password);
        }
        return $this->adminRepo->updateAdmin($admin, $data);
    }

    public function deleteAdmin($id)
    {
        return $this->adminRepo->deleteAdmin($id);
    }

}
