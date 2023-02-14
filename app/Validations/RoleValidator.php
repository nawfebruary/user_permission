<?php

namespace App\Validations;

use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class RoleValidator
{
    public function store($data)
    {
        return Validator::make($data, [
            'name' => 'required|max:100|unique:roles',
        ], [
            'name.requried' => 'Please give a role name'
        ]);
    }

    public function update($data, $id)
    {
        return Validator::make($data,  ['name' => 'required|max:100|unique:roles,name,' . $id
        ], [
            'name.requried' => 'Please give a role name'
        ]);
    }
}
