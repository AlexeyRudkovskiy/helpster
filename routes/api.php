<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    $user = $request->user();
    $organizations = $user->organizations;
    $data = $user->toArray();
    $data['organization_id'] = $request->user()->organizations()->first()->id;
    return $data;
});

Route::post('/widget/chat', [ \App\Http\Controllers\Widget\ChatController::class, 'create' ])->name('widget.chat.create');
Route::post('/widget/chat/{chat}', [ \App\Http\Controllers\Widget\ChatController::class, 'addMessage' ])->name('widget.chat.add_message');
Route::get('/widget/chat/{chat}', [ \App\Http\Controllers\Widget\ChatController::class, 'getHistory' ])->name('widget.chat.get_history');

Route::post('login', [ \App\Http\Controllers\API\AuthController::class, 'login' ])->name('api.login');
Route::post('register', [ \App\Http\Controllers\API\AuthController::class, 'register' ])->name('api.register');
Route::get('logout', [ \App\Http\Controllers\API\AuthController::class, 'logout' ])->name('api.logout');

Route::get('config', [ \App\Http\Controllers\ConfigController::class, 'index' ]);

Route::middleware(['auth:sanctum', 'online'])->group(function () {
    Route::get('overview', [ \App\Http\Controllers\API\OrganizationController::class, 'overview' ])->name('api.overview');

    Route::apiResource('agents', \App\Http\Controllers\API\AgentsController::class)->parameter('agents', 'user');
    Route::get('chats/{chat}/details', [\App\Http\Controllers\API\ChatsController::class, 'details'])->name('chats.details');
    Route::post('chats/{chat}/customer', [\App\Http\Controllers\API\ChatsController::class, 'updateCustomer'])->name('chats.customer');
    Route::post('chats/{chat}/attachment', [\App\Http\Controllers\API\ChatsController::class, 'uploadAttachment'])->name('chats.attachment');
    Route::get('chats/{chat}/status', [\App\Http\Controllers\API\ChatsController::class, 'updateStatus'])->name('chats.attachment');
    Route::apiResource('chats', \App\Http\Controllers\API\ChatsController::class);
});
