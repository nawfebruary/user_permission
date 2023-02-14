<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Repositories\Admin\AdminRepositoryInterface;
use App\Repositories\Permission\PermissionRepositoryInterface;
use App\Repositories\Role\RoleRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public $user, $roleRepo, $permissionRepo, $adminRepo;

    public function __construct(RoleRepositoryInterface $roleRepo, PermissionRepositoryInterface $permissionRepo, AdminRepositoryInterface $adminRepo)
    {
        $this->middleware(function ($request, $next) {
            $this->user  =  Auth::guard('admin')->user();
            return $next($request);
        });
        $this->roleRepo             = $roleRepo;
        $this->permissionRepo       = $permissionRepo;
        $this->adminRepo            = $adminRepo;
    }

    public function index()
    {
        if (is_null($this->user) || !$this->user->can('dashboard.view')) {
            abort(403, 'Sorry !! You are Unauthorized to view dashboard !');
        }

        $total_roles        = $this->roleRepo->getTotalCount();
        $total_admins       = $this->adminRepo->getTotalCount();
        $total_permissions  = $this->permissionRepo->getTotalCount();
        return view('backend.pages.dashboard.index', compact('total_admins', 'total_roles', 'total_permissions'));
    }
}
