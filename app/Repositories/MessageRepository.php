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
}
