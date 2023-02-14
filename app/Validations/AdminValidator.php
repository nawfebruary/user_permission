<?php

namespace App\Validations;

use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class AdminValidator
{
    public function store($data)
    {
        return Validator::make($data, [
            'name' => 'required|max:50',
            'email' => 'required|max:100|email|unique:admins',
            'username' => 'required|max:100|unique:admins',
            'password' => 'required|min:6|confirmed',
            'roles.*' => 'nullable|in:' . implode(',', Role::select('name')->get()->toArray()),
        ]);
    }

    public function update($data, $id)
    {
        return Validator::make($data, [
            'name' => 'required|max:50',
            'email' => 'required|max:100|email|unique:admins,email,' . $id,
            'username' => 'required|max:100',
            'password' => 'required|min:6|confirmed',
            'roles.*' => 'nullable|in:' . implode(',', Role::select('name')->get()->toArray()),
        ]);
    }
}
