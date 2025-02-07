<?php

namespace App\Repositories\Interfaces;

interface UserRepositoryInterface
{
    public function getAllPaginated(int $perPage = 10);
    public function create(array $data);
    public function update(int $id, array $data);
    public function delete(int $id);
    public function findById(int $id);
}
