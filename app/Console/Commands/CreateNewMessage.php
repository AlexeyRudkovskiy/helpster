<?php

namespace App\Console\Commands;

use App\Contracts\ChatRepositoryInterface;
use App\Events\NewMessage;
use App\Models\Chat;
use App\Models\Message;
use Illuminate\Console\Command;

class CreateNewMessage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-new-message';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
//        $chatId = '0302fbe0-6cee-458f-b70e-49cb75d0c90e';
        $chatId = '09007023-4f12-4d2c-b22a-ee9357102489';
        /** @var ChatRepositoryInterface $service */
        $service = app(ChatRepositoryInterface::class);

        $chat = Chat::find($chatId);
        $customer = $chat->customer;

        $message = $service->addMessage("hey hey hey", $chat, null, $customer);
        event(new NewMessage($message));
    }
}
