<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::orderBy('created_at', 'DESC')->paginate(25);
        return view('roles.index', [
            'roles' => $roles
        ]);
    }

    public function create()
    {
        $permissions = Permission::orderBy('name', 'ASC')->get();
        return view('roles.create', [
            'permissions' => $permissions
        ]);
    }

    public function store(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'name' => 'required|unique:roles|min:3|max:100',
        ]);

        if ($validated->passes()) {
            Role::create($validated->validated());
            if($request->has('permissions')) {
                $role = Role::findByName($validated->validated()['name']);
                $role->givePermissionTo($request->permissions);
            }
            return redirect()->route('roles.index')->with('success', 'Role added successfully');
        } else {
            return redirect()->route('roles.create')
                ->withErrors($validated)
                ->withInput();
        }
    }

    public function edit($id)
    {
        $role = Role::findOrFail($id);
        $hasPermissions = $role->permissions->pluck('name');
        $permissions = Permission::orderBy('name', 'ASC')->get();
        return view('roles.edit', [
            'role' => $role,
            'hasPermissions' => $hasPermissions,
            'permissions' => $permissions
        ]);
    }

    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        $validated = Validator::make($request->all(), [
            'name' => 'required|unique:permissions,name,' . $id . ',id|min:3|max:100',
        ]);

        if ($validated->passes()) {
            $role->name = $request->name;
            $role->save();

            if($request->has('permissions')) {
                $role->syncPermissions($request->permissions);
            } else {
                $role->syncPermissions([]);
            }
            return redirect()->route('roles.index')->with('success', 'Role updated successfully');
        } else {
            return redirect()->route('roles.edit', $id)->withErrors($validated)->withInput();
        }
    }

    public function destroy(Request $request)
    {
        $id = $request->id;
        $role = Role::findOrFail($id);
        if($role === null) {
            session()->flash('error', 'Role not found');
            return response()->json(['status' => false]);
        }
        $role->delete();
        session()->flash('success', 'Role deleted successfully');
        return response()->json(['status' => true]);
    }
}

