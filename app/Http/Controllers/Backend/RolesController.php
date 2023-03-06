<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\User;
use App\Repositories\Admin\AdminRepositoryInterface;
use App\Repositories\Permission\PermissionRepositoryInterface;
use App\Repositories\Role\RoleRepositoryInterface;
use App\Services\RoleService;
use App\Validations\RoleValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use DataTables;

class RolesController extends Controller
{
    public $user, $roleRepo, $permissionRepo, $adminRepo, $roleService, $roleValidator;

    public function __construct(
        RoleRepositoryInterface $roleRepo,
        PermissionRepositoryInterface $permissionRepo,
        AdminRepositoryInterface $adminRepo,
        RoleService $roleService,
        RoleValidator $roleValidator
    )
    {
        $this->middleware(function ($request, $next) {
            $this->user  =  Auth::guard('admin')->user();
            return $next($request);
        });
        $this->roleRepo             = $roleRepo;
        $this->permissionRepo       = $permissionRepo;
        $this->adminRepo            = $adminRepo;
        $this->roleService          = $roleService;
        $this->roleValidator        = $roleValidator;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (is_null($this->user) || !$this->user->can('role.view')) {
            abort(403, 'Sorry !! You are Unauthorized to view any role !');
        }

        if ($request->ajax()) {
            return $this->roleService->createDatatable($this->user);
        }
        return view('backend.pages.roles.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (is_null($this->user) || !$this->user->can('role.create')) {
            abort(403, 'Sorry !! You are Unauthorized to create any role !');
        }

        $all_permissions  = $this->permissionRepo->getAllPermissions();
        $permission_groups = Admin::getpermissionGroups();
        return view('backend.pages.roles.create', compact('all_permissions', 'permission_groups'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (is_null($this->user) || !$this->user->can('role.create')) {
            abort(403, 'Sorry !! You are Unauthorized to create any role !');
        }

        $validator = $this->roleValidator->store($request->all());

        if ($validator->fails()) {
            return redirect()
                ->withErrors($validator)
                ->withInput();
        }

        $res = $this->roleService->createRole($request);
        $res ? session()->flash('success', 'Role has been created !!') : session()->flash('error', 'Role was not created !!');
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(int $id)
    {
        if (is_null($this->user) || !$this->user->can('role.edit')) {
            abort(403, 'Sorry !! You are Unauthorized to edit any role !');
        }

        $role = $this->roleRepo->findAdminRoleById($id);
        $all_permissions = $this->permissionRepo->getAllPermissions();
        $permission_groups = Admin::getpermissionGroups();
        return view('backend.pages.roles.edit', compact('role', 'all_permissions', 'permission_groups'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {
        if (is_null($this->user) || !$this->user->can('role.edit')) {
            abort(403, 'Sorry !! You are Unauthorized to edit any role !');
        }

        $validator = $this->roleValidator->update($request->all(), $id);

        if ($validator->fails()) {
            return redirect()
                ->withErrors($validator)
                ->withInput();
        }


        $res = $this->roleRepo->updateRole($request, $id);
        session()->flash('success', 'Role has been updated !!');
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        if (is_null($this->user) || !$this->user->can('role.delete')) {
            abort(403, 'Sorry !! You are Unauthorized to delete any role !');
        }

        session()->flash('success', 'Role has been deleted !!');
        return back();
    }
}
