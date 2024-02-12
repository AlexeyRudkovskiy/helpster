<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use Illuminate\Http\Request;

class ConfigController extends Controller
{

    public function index(Request $request)
    {
        $organization = Organization::whereAppId($request->get('app_id'))->firstOrFail();
        return response()->json([
            'organization' => $organization,
            'assets' => [
                [
                    'type' => 'style',
                    'path' => \Vite::asset('resources/css/widget.css')
                ]
            ]
        ]);
    }

}
