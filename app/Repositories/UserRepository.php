<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;

class UserRepository implements UserRepositoryInterface
{
    protected $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function getAllPaginated(int $perPage = 10)
    {
        return $this->model->latest()->paginate($perPage);
    }

    public function create(array $data)
    {
        $user = $this->model->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password'])
        ]);

        if (isset($data['roles'])) {
            $user->syncRoles($data['roles']);
        }

        return $user;
    }

    public function update(int $id, array $data)
    {
        $user = $this->findById($id);

        $user->update([
            'name' => $data['name'],
            'email' => $data['email']
        ]);

        if (isset($data['roles'])) {
            $user->syncRoles($data['roles']);
        }

        return $user;
    }

    public function delete(int $id)
    {
        $user = $this->findById($id);
        return $user->delete();
    }

    public function findById(int $id)
    {
        return $this->model->findOrFail($id);
    }
}
