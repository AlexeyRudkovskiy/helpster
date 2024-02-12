<?php

namespace App\Http\Controllers\API;

use App\Contracts\ChatRepositoryInterface;
use App\Events\NewMessage;
use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\Customer;
use App\Models\File;
use App\Models\Message;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class ChatsController extends Controller
{

    public function __construct(
        private ChatRepositoryInterface $chatRepository
    )
    {
    }

    public function index()
    {
        /** @var User $user */
        $user = Auth::user();
        /** @var Organization $organization */
        $organization = $user->organizations()->first();

        return $organization->chats()->latest()->get();
    }

    public function show(Chat $chat)
    {
        if ($chat->read === 0) {
            $chat->read = true;
            $chat->timestamps = false;
            $chat->save();
        }

        return Chat::whereId($chat->id)->with('messages')->first();
    }

    public function update(Chat $chat, Request $request)
    {
        $files = collect($request->get('files', []));
        $files = $files->map(fn ($file) => File::find($file['id']));

        $text = $request->get('message');
        $message = $this->chatRepository->addMessage($text, $chat, auth()->user());

        $files->each(function (File $file) use ($message) {
            $file->message_id = $message->id;
            $file->save();
        });

        if ($chat->user_id === null) {
            $chat->user_id = auth()->id();
            $chat->save();
        }

        $message = $message->fresh();

        NewMessage::dispatch($message);

        return response()->json($message);
    }

    public function details(Chat $chat)
    {
        /** @var Customer $customer */
        $otherChats = $chat->customer->chats()
            ->latest()
            ->with([ 'user' ])
            ->get()
            ->map(function (Chat $chat) {
                $data = $chat->toArray();
                $data['first_message'] = $chat->messages()->orderBy('id', 'asc')->first();
                return $data;
            });

        return response()->json([
            'customer' => $chat->customer()->get(),
            'chats' => $otherChats,
            'files' => $chat->files()->latest()->get()
        ])->setStatusCode(Response::HTTP_OK);
    }

    public function updateCustomer(Chat $chat, Request $request)
    {
        $request->validate([
            'name' => [ 'required', 'min:4' ]
        ]);

        $name = $request->get('name');
        /** @var Customer $customer */
        $customer = $chat->customer;
        $customer->full_name = $name;
        $customer->save();

        return response()->json()->setStatusCode(Response::HTTP_OK);
    }

    public function uploadAttachment(Chat $chat, Request $request)
    {
        $files = $request->file('files');
        $uploadedFiles = collect([]);

        foreach ($files as $uploadedFile) {
            $filename = $uploadedFile->getClientOriginalName();
            $newFilename = sha1($uploadedFile->getClientOriginalName() . now()->timestamp) . '.' . $uploadedFile->getClientOriginalExtension();
            $path = $uploadedFile->storePubliclyAs('public/uploads', $newFilename);
            $file = new File();
            $file->path = asset('storage/uploads/' . $newFilename);
            $file->filename = $filename;

            $chat->files()->save($file);

            $uploadedFiles->push($file);
        }

        return response()->json(
            $uploadedFiles->map(function (File $file) {
                return [
                    'id' => $file->id,
                    'name' => $file->filename
                ];
            })
        )->setStatusCode(Response::HTTP_OK);
    }

    public function updateStatus(Chat $chat)
    {
        $chat->timestamps = false;
        $chat->read = true;
        $chat->save();

        return response()->json();
    }

}
