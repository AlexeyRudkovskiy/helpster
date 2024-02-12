<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class OrganizationController extends Controller
{

    public function overview()
    {
        /** @var User $user */
        $user = auth()->user();

        /** @var Organization $organization */
        $organization = $user->organizations()->first();

        return response()->json([
            'agents' => $organization->users()->count(),
            'agents_online' => $organization->users()->where('last_online', '>', now()->subMinutes(10))->count(),
            'tickets' => $organization->chats()->whereRead(0)->count()
        ])->setStatusCode(Response::HTTP_OK);
    }

}
