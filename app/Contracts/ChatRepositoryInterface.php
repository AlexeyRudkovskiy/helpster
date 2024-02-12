<?php

namespace App\Contracts;

use App\Models\Chat;
use App\Models\Customer;
use App\Models\Message;
use App\Models\User;

interface ChatRepositoryInterface
{

    public function create(): Chat;

    public function addMessage(string $message, Chat $chat, ?User $user = null, ?Customer $customer = null): Message;

}
