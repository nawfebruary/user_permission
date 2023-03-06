<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Repositories\Admin\AdminRepositoryInterface;
use App\Repositories\Permission\PermissionRepositoryInterface;
use App\Repositories\Role\RoleRepositoryInterface;
use App\Services\AdminService;
use App\Validations\AdminValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminsController extends Controller
{
    public $user, $adminRepo, $roleRepo, $permissionRepo, $adminValidator, $adminService;

    public function __construct(
        AdminRepositoryInterface $adminRepo,
        RoleRepositoryInterface $roleRepo,
        PermissionRepositoryInterface $permissionRepo,
        AdminService $adminService,
        AdminValidator $adminValidator
    )
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::guard('admin')->user();
            return $next($request);
        });

        $this->adminRepo        = $adminRepo;
        $this->roleRepo         = $roleRepo;
        $this->permissionRepo   = $permissionRepo;
        $this->adminService     = $adminService;
        $this->adminValidator   = $adminValidator;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (is_null($this->user) || !$this->user->can('admin.view')) {
            abort(403, 'Sorry !! You are Unauthorized to view any admin !');
        }

        if ($request->ajax()) {
            return $this->adminService->createDatatable($this->user);
        }
        return view('backend.pages.admins.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (is_null($this->user) || !$this->user->can('admin.create')) {
            abort(403, 'Sorry !! You are Unauthorized to create any admin !');
        }

        $roles  = $this->roleRepo->getAllRoles();
        return view('backend.pages.admins.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (is_null($this->user) || !$this->user->can('admin.create')) {
            abort(403, 'Sorry !! You are Unauthorized to create any admin !');
        }

        // Validation Data
        $validator = $this->adminValidator->store($request->all());

        if ($validator->fails()) {
            return redirect()
                ->withErrors($validator)
                ->withInput();
        }

        // Retrieve the validated input...
        $validated = $validator->validated();

        $res = $this->adminService->createAdmin($validated);

        $res ? session()->flash('success', 'Admin has been created !!') : session()->flash('error', 'Admin was not created !!');
        return redirect()->route('admin.admins.index');
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
        if (is_null($this->user) || !$this->user->can('admin.edit')) {
            abort(403, 'Sorry !! You are Unauthorized to edit any admin !');
        }

        $admin = $this->adminRepo->findAdminById($id);
        $roles  = $this->roleRepo->getAllRoles();
        return view('backend.pages.admins.edit', compact('admin', 'roles'));
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
        if (is_null($this->user) || !$this->user->can('admin.edit')) {
            abort(403, 'Sorry !! You are Unauthorized to edit any admin !');
        }

        $validator = $this->adminValidator->update($request->all(), $id);

        if ($validator->fails()) {
            return redirect()
                ->withErrors($validator)
                ->withInput();
        }

        // Retrieve the validated input...
        $validated = $validator->validated();

        $res = $this->adminService->updateAdmin($id, $validated);
        $res ? session()->flash('success', 'Admin has been updated !!') : session()->flash('error', 'Admin was not updated !!');
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
        if (is_null($this->user) || !$this->user->can('admin.delete')) {
            abort(403, 'Sorry !! You are Unauthorized to delete any admin !');
        }

        $res = $this->adminService->deleteAdmin($id);
        $res ?session()->flash('success', 'Admin has been deleted !!') : session()->flash('error', 'Admin was not deleted !!');
        return back();
    }
}
