<?php

use App\Http\Controllers\ConversationController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\PusherController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::resource('groups', GroupController::class);
Route::resource('conversations', ConversationController::class);
Route::post('/pusher/auth', [PusherController::class, 'pusherAuth'])->middleware('auth');
Route::post('/groups/{group}/add-user', [GroupController::class, 'addUserToGroup'])->middleware('auth');
Route::get('users/add-group/{group}', [UserController::class, 'index']);
