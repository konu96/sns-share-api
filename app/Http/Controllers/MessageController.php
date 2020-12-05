<?php

namespace App\Http\Controllers;

use App\Repositories\MessageRepositoryInterface;
use DB;
use Exception;
use Str;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    private $messageRepository;

    public function __construct(MessageRepositoryInterface $messageRepository) {
        $this->messageRepository = $messageRepository;
    }

    public function store(Request $request) {
        $params = $request->json()->all();
        $content = $params['message'];
        $uuid = Str::uuid();
        $filePath = $uuid->toString();

        $this->messageRepository->create([
            'id' => $uuid,
            'content' => $content,
            'file_path' => $filePath
        ]);

        return response()->json(['uuid' => $uuid]);
    }
}
