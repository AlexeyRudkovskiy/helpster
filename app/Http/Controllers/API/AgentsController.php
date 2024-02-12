<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AgentsController extends Controller
{

    public function index()
    {
        /** @var Organization $organization */
        $organization = auth()->user()->organizations()->first();

        return $organization->users()
            ->latest()
            ->paginate();
    }

    public function show(User $user)
    {
        return response()->json($user)->setStatusCode(Response::HTTP_OK);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => ['required', Rule::unique('users', 'email')],
            'password' => ['required', 'min:6'],
            're_password' => ['required_with:password', 'same:password']
        ]);

        $user = new \App\Models\User();
        $user->fill($request->only([ 'name', 'email' ]));
        $user->password = Hash::make($request->get('password'));

        $user = auth()->user()->organizations()->first()->users()->save($user);

        return $user;
    }

    public function update(User $user, Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => ['required', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => ['optional', 'min:6'],
            're_password' => ['required_with:password', 'same:password']
        ]);

        $user->fill($request->only([ 'name', 'email' ]));

        $password = $request->get('password', null);
        if ($password !== null) {
            $user->password = Hash::make($password);
        }

        $user->save();

        return $user;

    }

    public function destroy(User $user)
    {
        $user->delete();
        return response()->json([
            'status' => 'success'
        ])->setStatusCode(Response::HTTP_OK);
    }

}
