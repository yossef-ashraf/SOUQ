<?php

namespace App\Http\Controllers\Admin;

use DB;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    function __construct()
    {
        //  $this->middleware('permission:role-list|role-create|role-edit|role-delete', ['only' => ['index','store']]);
        //  $this->middleware('permission:role-create', ['only' => ['create','store']]);
        //  $this->middleware('permission:role-edit', ['only' => ['edit','update']]);
        //  $this->middleware('permission:role-delete', ['only' => ['destroy']]);

    }

    public function show()
    {
        $roles = Role::orderBy('id', 'DESC')->get();
        return $this->apiResponse(200, __('lang.Successfully'), null, $roles);
    }

    public function showOne(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:roles,id',
            ]);
            if ($validator->fails()) {
            return $this->apiResponse(400, __('lang.validationError'), $validator->errors());
            }
        $role = Role::findOrFail($request->id);
        $permissions = Permission::get();
        $rolePermissions = Permission::join("role_has_permissions","role_has_permissions.permission_id","=","permissions.id")
        ->where("role_has_permissions.role_id",$request->id)
        ->get();
        return $this->apiResponse(200, __('lang.Successfully'), null, ['role' => $role,'permissions'=>$permissions ,'rolePermissions' => $rolePermissions]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:roles,name',
            'permissions' => 'required|array',
        ]);

        if ($validator->fails()) {
            return $this->apiResponse(400, __('lang.validationError'), $validator->errors());
        }

        $role = Role::create(['name' => $request->input('name')]);
        $role->givePermissionTo($request->input('permissions'));

        return $this->apiResponse(200, __('lang.Successfully'), null, $role);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:roles,id',
            'name' => 'required',
            'permissions' => 'required|array',
        ]);

        if ($validator->fails()) {
            return $this->apiResponse(400, __('lang.validationError'), $validator->errors());
        }

        $role = Role::findOrFail($request->id);
        $role->name = $request->input('name');
        $role->save();

        $role->syncPermissions($request->input('permissions'));

        return $this->apiResponse(200, __('lang.Successfully'), null, $role);
    }

    public function destroy(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:roles,id',
            ]);
            if ($validator->fails()) {
            return $this->apiResponse(400, __('lang.validationError'), $validator->errors());
            }

        $role = Role::findOrFail($request->id);
        $role->delete();

        return $this->apiResponse(200, __('lang.Successfully'));
    }



}
