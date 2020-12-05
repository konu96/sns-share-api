<?php

namespace App\Repositories;


use App\Models\Message;

interface MessageRepositoryInterface {
    public function create(array $params);
    public function findById(string $id): ?Message;
}
