<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::orderBy('created_at', 'DESC')->paginate(25);
        return view('permissions.index', [
            'permissions' => $permissions
        ]);
    }

    public function create()
    {
        return view('permissions.create');
    }

    public function store(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'name' => 'required|unique:permissions|min:3|max:100',
        ]);

        if ($validated->passes()) {
            Permission::create($validated->validated());
            return redirect()->route('permissions.index')->with('success', 'Permission added successfully');
        } else {
            return redirect()->route('permissions.create')
                ->withErrors($validated)
                ->withInput();
        }
    }

    public function edit($id)
    {
        $permission = Permission::findOrFail($id);
        return view('permissions.edit', [
            'permission' => $permission
        ]);
    }

    public function update(Request $request, $id)
    {
        $validated = Validator::make($request->all(), [
            'name' => 'required|unique:permissions,name,' . $id . ',id|min:3|max:100',
        ]);

        if ($validated->passes()) {
            Permission::findOrFail($id)->update($validated->validated());
            return redirect()->route('permissions.index')->with('success', 'Permission updated successfully');
        } else {
            return redirect()->route('permissions.edit', $id)->withErrors($validated)->withInput();
        }
    }

    public function destroy(Request $request)
    {
        $id = $request->id;
        $permission = Permission::findOrFail($id);
        if($permission === null) {
            session()->flash('error', 'Permission not found');
            return response()->json(['status' => false]);
        }
        $permission->delete();
        session()->flash('success', 'Permission deleted successfully');
        return response()->json(['status' => true]);
    }
}

