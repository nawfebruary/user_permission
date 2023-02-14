<?php

namespace App\Services;

use App\Models\Admin;
use App\Models\User;
use App\Repositories\Admin\AdminRepositoryInterface;
use App\Repositories\Role\RoleRepositoryInterface;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use DataTables;

class RoleService
{

    private $roleRepo;

    public function __construct(RoleRepositoryInterface $roleRepo)
    {
        $this->roleRepo = $roleRepo;
    }

    public function createDatatable($user)
    {
        $roles = $this->roleRepo->getAllRoles();
        return DataTables::of($roles)->addIndexColumn()
            ->addColumn('permissions', function($row){
                $btn = '';
                foreach ($row->permissions as $perm) {
                    $btn.= '<span class="badge badge-info mr-1">'
                        .$perm->name.'
                                </span>';
                }
                return $btn;
            })
            ->addColumn('action', function($row) use ($user){
                $actionBtn = '';
                if($user->can('role.edit'))
                    $actionBtn = '<a href="'.route('admin.roles.edit', $row->id) .'" class="btn btn-success btn-sm text-white mr-1">Edit</a>';
                if($user->can('role.delete'))
                    $actionBtn.= '<a class="btn btn-danger btn-sm text-white" href="'.route('admin.roles.destroy', $row->id) .'"
                                        onclick="event.preventDefault(); document.getElementById(\'delete-form-'. $row->id.'\').submit();">
                                        Delete
                                    </a>
                                    <form id="delete-form-{{ $role->id }}" action="'.route('admin.roles.destroy', $row->id) .'" method="POST" style="display: none;">
                                           @method(\'DELETE\')
                                         @csrf</form>';
                return $actionBtn;
            })
            ->rawColumns(['permissions', 'action'])
            ->make(true);
    }

    public function createRole($request)
    {
        $data = ['name' => $request->name, 'guard_name' => 'admin'];
        $permissions = $request->input('permissions');

        return $this->roleRepo->createRole($data,$permissions);
    }

    public function updateRole($request, $id)
    {
        $role_name  = $request->name;
        $permissions = $request->input('permissions');
        return $this->roleRepo->updateRole($role_name,$permissions, $id);
    }

    public function deleteRole($id)
    {
        return $this->roleRepo->deleteRole($id);
    }

}

