<?php

namespace App\Services;

use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class UserService
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getAllUsers(int $perPage = 10)
    {
        return $this->userRepository->getAllPaginated($perPage);
    }

    public function createUser(array $data)
    {
        $validator = Validator::make($data, [
            'name' => 'required|string|min:3|max:255',
            'email' => 'required|string|email|min:3|max:255|unique:users,email',
            'password' => 'required|string|min:8',
            'password_confirmation' => 'required|string|min:8|same:password',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $this->userRepository->create($data);
    }

    public function updateUser(int $id, array $data)
    {
        $validator = Validator::make($data, [
            'name' => 'required|string|min:3|max:255',
            'email' => 'required|string|email|min:3|max:255|unique:users,email,'.$id.',id',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $this->userRepository->update($id, $data);
    }

    public function deleteUser(int $id)
    {
        return $this->userRepository->delete($id);
    }

    public function findUser(int $id)
    {
        return $this->userRepository->findById($id);
    }
}
