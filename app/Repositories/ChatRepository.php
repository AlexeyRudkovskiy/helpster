<?php

namespace App\Repositories;

use App\Contracts\ChatRepositoryInterface;
use App\Models\Chat;
use App\Models\Customer;
use App\Models\Message;
use App\Models\User;

class ChatRepository implements ChatRepositoryInterface
{

    public function create(): Chat
    {
        $chat = new Chat();
        $chat->save();

        return $chat;
    }

    public function addMessage(string $message, Chat $chat, ?User $user = null, ?Customer $customer = null): Message
    {
        $newMessage = new Message();
        $newMessage->message = $message;
        $newMessage->chat_id = $chat->id;

        if ($user !== null) {
            $newMessage->user_id = $user->id;
        }

        if ($customer !== null) {
            $newMessage->customer_id = $customer->id;
        }

        $newMessage->save();

        if ($user === null) {
            $chat->read = false;
        }

        $chat->updated_at = now();
        $chat->save();

        return $newMessage;
    }

}
