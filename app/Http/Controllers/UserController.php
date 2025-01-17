<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->paginate(10);
        return view('users.index',[
            'users' => $users
        ]);
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        dd($request->all());
    }

    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        $roles = Role::orderBy('name', 'ASC')->get();
        $hasRoles = $user->roles->pluck('name');
        return view('users.edit',[
            'user' => $user,
            'roles' => $roles,
            'hasRoles' => $hasRoles
        ]);
    }

    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);
        $validated = Validator::make($request->all(),[
            'name' => 'required|string|min:3|max:255',
            //'email' => 'required|string|email|max:255|unique:users,email,'.$id.',id',
            //'password' => 'nullable|string|min:8',
        ]);

        if($validated->fails()){
            return redirect()->route('users.edit', $id)->withErrors($validated)->withInput();
        }

        $user->name = $request->name;
        $user->save();

        $user->syncRoles($request->roles);

        return redirect()->route('users.index')->with('success', 'User updated successfully');

        dd($request->all());
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully');
    }
}
