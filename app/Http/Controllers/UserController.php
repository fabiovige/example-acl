<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->middleware(['permission:view users|edit users']);
        $this->userService = $userService;
    }

    public function index()
    {
        $users = $this->userService->getAllUsers(10);
        return view('users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::orderBy('name', 'ASC')->get();
        return view('users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        try {
            $this->userService->createUser($request->all());
            return redirect()->route('users.index')->with('success', 'User created successfully');
        } catch (ValidationException $e) {
            return redirect()->route('users.create')
                ->withErrors($e->validator)
                ->withInput();
        }
    }

    public function edit(string $id)
    {
        $user = $this->userService->findUser($id);
        $roles = Role::orderBy('name', 'ASC')->get();
        $hasRoles = $user->roles->pluck('name');

        return view('users.edit', compact('user', 'roles', 'hasRoles'));
    }

    public function update(Request $request, string $id)
    {
        try {
            $this->userService->updateUser($id, $request->all());
            return redirect()->route('users.index')->with('success', 'User updated successfully');
        } catch (ValidationException $e) {
            return redirect()->route('users.edit', $id)
                ->withErrors($e->validator)
                ->withInput();
        }
    }

    public function destroy(Request $request)
    {
        try {
            $this->userService->deleteUser($request->id);
            session()->flash('success', 'User deleted successfully');
            return response()->json(['status' => true]);
        } catch (\Exception $e) {
            session()->flash('error', 'User not found');
            return response()->json(['status' => false]);
        }
    }
}
