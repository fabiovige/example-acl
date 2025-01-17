<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware(['permission:view users|edit users']);
    }

    public function index()
    {
        $users = User::latest()->paginate(10);
        return view('users.index',[
            'users' => $users
        ]);
    }

    public function create()
    {
        $roles = Role::orderBy('name', 'ASC')->get();
        return view('users.create',[
            'roles' => $roles
        ]);
    }

    public function store(Request $request)
    {
        $validated = Validator::make($request->all(),[
            'name' => 'required|string|min:3|max:255',
            'email' => 'required|string|email|min:3|max:255|unique:users,email',
            'password' => 'required|string|min:8',
            'password_confirmation' => 'required|string|min:8|same:password',
        ]);

        if($validated->fails()){
            return redirect()->route('users.create')->withErrors($validated)->withInput();
        }

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();

        $user->syncRoles($request->roles);

        return redirect()->route('users.index')->with('success', 'User created successfully');
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
            'email' => 'required|string|email|min:3|max:255|unique:users,email,'.$id.',id',
        ]);

        if($validated->fails()){
            return redirect()->route('users.edit', $id)->withErrors($validated)->withInput();
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        $user->syncRoles($request->roles);

        return redirect()->route('users.index')->with('success', 'User updated successfully');
    }

    public function destroy(Request $request)
    {
        $id = $request->id;
        $user = User::findOrFail($id);
        if($user === null) {
            session()->flash('error', 'User not found');
            return response()->json(['status' => false]);
        }
        $user->delete();
        session()->flash('success', 'User deleted successfully');
        return response()->json(['status' => true]);
    }
}
