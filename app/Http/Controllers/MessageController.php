<?php

namespace App\Http\Controllers;

use App\Repositories\MessageRepositoryInterface;
use DB;
use Exception;
use Str;
use Illuminate\Http\Request;
use Storage;

class MessageController extends Controller
{
    private $messageRepository;

    public function __construct(MessageRepositoryInterface $messageRepository) {
        $this->messageRepository = $messageRepository;
    }

    public function store(Request $request) {
        $params = $request->json()->all();

        $image = $params['image']['data'];
        $decodedImage = base64_decode($image);

        $content = $params['message'];

        $uuid = DB::transaction(function() use ($decodedImage, $content) {
            $uuid = Str::uuid();
            $filePath = $uuid->toString();
            $this->messageRepository->create([
                'id' => $uuid,
                'content' => $content,
                'file_path' => $filePath
            ]);

            $isSuccess = Storage::disk('s3')->put($filePath, $decodedImage);
            if (!$isSuccess) {
                throw new Exception('ファイルのアップロードに失敗しました。');
            }

            Storage::disk('s3')->setVisibility($filePath, 'public');

            return $uuid;
        });

        return response()->json(['uuid' => $uuid]);
    }

    public function show(string $id) {
        $message = $this->messageRepository->findById($id);
        $imageUrl = config('app.image_url');

        return response()->json([
            'message' => $message->content,
            'url' => "{$imageUrl}/{$message->file_path}"
        ]);
    }
}
