<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TokenManager;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
use App\Http\Controllers\Api;
Route::get('/user', function (Request $request) {
    $tokenManager = new TokenManager();
    dd($tokenManager->getUserInfoByJwt($request->api_token));
})->middleware('api_guard');



Route::get('v0/openapi',[Api::class, 'openapi']);
Route::get('v0/info',[Api::class, 'info']);
Route::get('v0/auth/login',[Api::class, 'login']);

// Route::get('oauth2/auth/login',[Api::class, 'login']);
// Route::get('oauth2/auth/revoke',[Api::class, 'login']);
// Route::get('oauth2/credentials/list',[Api::class, 'login']);
// Route::get('oauth2/credentials/info',[Api::class, 'login']);
// Route::get('oauth2/credentials/authorize',[Api::class, 'login']);
// Route::get('oauth2/credentials/sendOTP',[Api::class, 'login']);
// Route::get('oauth2/signatures/signHash',[Api::class, 'login']);