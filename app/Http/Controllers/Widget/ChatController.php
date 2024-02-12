<?php

namespace App\Http\Controllers\Widget;

use App\Contracts\ChatRepositoryInterface;
use App\Events\NewMessage;
use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\Customer;
use App\Models\Organization;
use Illuminate\Http\Request;

class ChatController extends Controller
{

    public function __construct(
        private ChatRepositoryInterface $chatRepository
    )
    {
    }

    public function create(Request $request)
    {
        $app_id = $request->get('app_id', null);
        $customer_identifier = $request->get('identifier', null);

        /** @var Organization $organization */
        $organization = Organization::whereAppId($app_id)->firstOrFail();
        $customer = null;

        if ($customer_identifier !== null) {
            $customer = $organization->customers()
                ->whereUniqueId($customer_identifier)
                ->first();

            if ($customer === null) {
                $customer = new Customer();
                $customer->full_name = $request->get('name');
                $customer->unique_id = $customer_identifier;
                $customer->description = '';
                $organization->customers()->save($customer);
            }
        } else {
            $customer = new Customer();
            $customer->full_name = 'Невідомий клієнт';
            $customer->unique_id = sha1(now()->timestamp);
            $customer->description = '';
            $organization->customers()->save($customer);
        }

        $chat = new Chat();
        if ($customer !== null) {
            $chat->customer_id = $customer->id;
        }
        $chat->user_id = null;
        $organization->chats()->save($chat);

        $message = $this->chatRepository->addMessage($request->get('message'), $chat, null, $customer);

        return response()->json([
            'id' => $chat->id,
            'message' => $message,
            'customer' => $customer
        ]);
    }

    public function addMessage(Chat $chat, Request $request)
    {
        $customer = $chat->customer;

        $message = $this->chatRepository->addMessage($request->get('message'), $chat, null, $customer);
        NewMessage::dispatch($message);

        return $message;
    }

    public function getHistory(Chat $chat, Request $request)
    {
        /** @var Organization $organization */
        $organization = $chat->organization;

        $appId = $request->get('app_id');

        if ($organization->app_id !== $appId) {
            abort(403);
        }

        return $chat->messages()->orderBy('id', 'asc')->get();
    }

}
