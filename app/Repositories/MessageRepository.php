<?php

namespace App\Repositories;

use App\Models\Message;

class MessageRepository implements MessageRepositoryInterface {
    private $message;
    public function __construct(Message $message) {
        $this->message = $message;
    }

    public function create(array $params) {
        $this->message->create($params);
    }

    public function findById(string $id): ?Message {
        return $this->message->find($id);
    }
}
