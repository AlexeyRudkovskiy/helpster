<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{

    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', Rule::exists('users', 'email')],
            'password' => ['required', 'min:4']
        ]);

        if (Auth::attempt($request->only(['email', 'password']))) {
            $request->session()->regenerate();

            return response()->json([ 'status' => 'success' ])->setStatusCode(Response::HTTP_OK);
        }

        return response()->json([
            'message' => 'Credentials are wrong'
        ])->setStatusCode(Response::HTTP_UNAUTHORIZED);
    }

    public function register(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            'name' => [ 'required', 'min:4' ],
            'password' => ['required', 'min:4'],
            'organization_name' => [ 'required', 'min:4', Rule::unique('organizations', 'name') ]
        ]);

        $organization = new Organization();
        $organization->name = $request->get('organization_name');
        $organization->save();

        $user = new User();
        $user->email = $request->get('email');
        $user->password = $request->get('password');
        $user->name = $request->get('name');
        $organization->users()->save($user);
        $user = User::where('email', $user->email)->first();

        Auth::loginUsingId($user->id);

        return response()->json()->setStatusCode(Response::HTTP_CREATED);
    }

    public function logout()
    {
        auth()->logout();
        session()->regenerate(true);

        return response()->json()->setStatusCode(Response::HTTP_OK);
    }

}
