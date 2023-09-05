<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

// ミドルウェアで認証をかけてログインしてないとアクセスできないAPIを定義できる
Route::middleware(['auth:sanctum'])
    ->name('api.')
    ->group(function () {
        Route::get('/me', function () {
            return response()->json([
                "id" => 1,
                "name" => "ニックネーム",
                "email" => "user@example.com",
                "icon_url" => "http://localhost/users/image/1",
            ]);
        });

        Route::post('/my/icons', function () {
            return 'http://localhost/users/image/1';
        });
    });
