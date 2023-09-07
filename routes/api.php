<?php

use App\Http\Controllers\Api\MyResourceController;
use App\Http\Controllers\Api\TagController;
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


// ミドルウェアで認証をかけてログインしてないとアクセスできないAPIを定義できる
Route::middleware(['auth:sanctum'])
    ->name('api.')
    ->group(function () {

        // ログインしているユーザーを取得
        Route::get('/user', function (Request $request) {
            return $request->user();
        });

        Route::get('/me', [MyResourceController::class, 'me'])->name('me');

        Route::prefix('/my')
            ->name('my.')
            ->group(function () {
                Route::post('/icons', [MyResourceController::class, 'updateIcons'])
                    ->name('icons');

                Route::get('/tags', [MyResourceController::class, 'tags'])
                    ->name('tags');
            });

        Route::prefix('tags')
            ->name('tags.')
            ->group(function () {

                Route::get('', [TagController::class, 'index'])->name('index');
                Route::post('', [TagController::class, 'store'])->name('store');

                Route::prefix('/{id}')->group(function () {
                    Route::post('/join', [TagController::class, 'join'])
                        ->name('join');
                    Route::post('/leave', [TagController::class, 'leave'])
                        ->name('leave');
                });
            });
    });
