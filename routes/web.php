<?php

use App\Http\Controllers\UnchartedChallengeController;
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
    return ['Laravel' => app()->version()];
});

Route::get('/users/image/{userId}', [UserController::class, 'showIcon'])
    ->name('web.users.image');
Route::get('/attachments/{attachmentId}', [AttachmentController::class, 'download'])
    ->name('web.attachments');

//↓テスト用
Route::get('/uncharted', [UnchartedChallengeController::class, 'store'])
    ->name('web.uncharted');

require __DIR__ . '/auth.php';
