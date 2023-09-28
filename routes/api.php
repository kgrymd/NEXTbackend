<?php

use App\Http\Controllers\Api\ChatGroupController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\FavoritesController;
use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Api\MyResourceController;
use App\Http\Controllers\Api\PrefectureController;
use App\Http\Controllers\Api\TagController;
use App\Http\Controllers\Api\RecruitmentController;
use App\Http\Controllers\Api\RecruitmentTagController;
use App\Http\Controllers\Api\UserController;
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

Route::prefix('prefectures')
    ->name('prefectures.')
    ->group(
        function () {
            Route::get('', [PrefectureController::class, 'index'])->name('index');
        }
    );

Route::get('users/{id}', [UserController::class, 'show'])->name('show');


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

                // Route::put('/{id}/tags', [MyResourceController::class, 'updateTags']);

                Route::patch('/data', [MyResourceController::class, 'updateData'])->name('updateData');

                // Route::get('/tags', [MyResourceController::class, 'tags'])
                //     ->name('tags');

                Route::get('/participations', [MyResourceController::class, 'myParticipations'])
                    ->name('participations');

                Route::get('/chat-groups', [MyResourceController::class, 'chat_groups'])
                    ->name('chat_groups');

                Route::get('/likedRecruitments', [MyResourceController::class, 'likedRecruitments'])
                    ->name('likedRecruitments');

                Route::get('/createdRecruitments', [MyResourceController::class, 'createdRecruitments'])
                    ->name('createdRecruitments');

                Route::post('/unchartedChallenge', [MyResourceController::class, 'unchartedChallenge'])
                    ->name('unchartedChallenge');

                Route::get('/unchartedChallenges', [MyResourceController::class, 'unchartedChallenges'])
                    ->name('unchartedChallenges');
                Route::get('/currentUnchartedChallenge', [MyResourceController::class, 'currentUnchartedChallenge'])
                    ->name('currentUnchartedChallenge');
            });

        Route::prefix('tags')
            ->name('tags.')
            ->group(function () {

                Route::get('', [TagController::class, 'index'])->name('index');
                Route::post('', [TagController::class, 'store'])->name('store');

                // Route::prefix('/{id}')->group(function () {
                //     Route::post('/join', [TagController::class, 'join'])
                //         ->name('join');
                //     Route::post('/leave', [TagController::class, 'leave'])
                //         ->name('leave');
                // });
            });

        Route::prefix('recruitments')
            ->name('recruitments.')
            ->group(
                function () {
                    Route::get('', [RecruitmentController::class, 'index'])->name('index');

                    Route::post('', [RecruitmentController::class, 'creation']);
                    Route::post('/{recruitmentId}/tags', [RecruitmentTagController::class, 'update']);

                    Route::get('/{id}', [RecruitmentController::class, 'show'])->name('show');
                    Route::post('/{id}', [RecruitmentController::class, 'update'])->name('update');

                    // Route::get('/suggestions', [RecruitmentController::class, 'suggestions'])->name('suggestions');

                    Route::get('/suggestions/nearby', [RecruitmentController::class, 'nearbyRecruitments'])->name('nearbyRecruitments');

                    Route::get('/suggestions/suggest', [RecruitmentController::class, 'suggestRecruitments'])->name('suggestRecruitments');

                    Route::get('/search/keyword', [RecruitmentController::class, 'searchRecruitments'])->name('searchRecruitments');

                    Route::post('/{recruitmentId}/favorite', [FavoritesController::class, 'addFavorite'])->name('favorite');
                    Route::delete('/{recruitmentId}/unfavorite', [FavoritesController::class, 'removeFavorite'])->name('unfavorite');
                }
            );

        Route::prefix('comments')
            ->name('comments.')
            ->group(function () {
                Route::post('', [CommentController::class, 'store'])->name('store');
            });


        Route::prefix('participants')
            ->name('participants.')
            ->group(function () {
                Route::post('', [RecruitmentController::class, 'join'])->name('join');
            });


        Route::prefix('/chat-groups')
            ->name('chat-groups.')
            ->group(function () {
                // Route::get('', [ChatGroupController::class, 'index'])->name('index');

                Route::post('', [ChatGroupController::class, 'store'])->name('store');

                Route::prefix('/{uuid}')->group(function () {

                    Route::get('', [ChatGroupController::class, 'show'])->name('show');

                    Route::prefix('/messages')
                        ->name('messages.')
                        ->group(function () {
                            Route::get('', [MessageController::class, 'index'])
                                ->name('index');

                            Route::get('/polling', [MessageController::class, 'polling'])
                                ->name('polling');

                            Route::post('', [MessageController::class, 'store'])
                                ->name('store');

                            // Route::delete('/{id}', [MessageController::class, 'destroy'])
                            //     ->name('destroy');
                        });

                    Route::post('/join', [ChatGroupController::class, 'join'])
                        ->name('join');

                    Route::post('/leave', [ChatGroupController::class, 'leave'])
                        ->name('leave');
                });
            });
    });
