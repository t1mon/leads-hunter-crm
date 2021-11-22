<?php

use App\Http\Controllers\Project\TelegramIDController;
use Illuminate\Support\Facades\Route;

/*
Route::get('dashboard', function(){

})->name('dashboard');
*/

Route::get('dashboard', 'ShowDashboard')->name('dashboard');
Route::resource('posts', 'PostController');
Route::delete('/posts/{post}/thumbnail', 'PostThumbnailController@destroy')->name('posts_thumbnail.destroy');
Route::resource('users', 'UserController')->only(['index', 'edit', 'update']);
Route::resource('comments', 'CommentController')->only(['index', 'edit', 'update', 'destroy']);
Route::resource('media', 'MediaLibraryController')->only(['index', 'show', 'create', 'store', 'destroy']);

Route::prefix('settings')->as('settings.')->group(function() {

    Route::prefix('telegram')->as('telegram.')->group(function () {
        Route::get('/', [TelegramIDController::class, 'telegram'])->name('main');

        Route::get('updates', [TelegramIDController::class, 'getUpdates'])->name('updates');

        //Route::post('webhook', [TelegramIDController::class, 'webhook'])->name('webhook');
        Route::get('webhook/info', [TelegramIDController::class, 'webhookInfo'])->name('webhook.info');
        Route::get('webhook/set', [TelegramIDController::class, 'setWebhook'])->name('webhook.update');
        Route::get('webhook/delete', [TelegramIDController::class, 'deleteWebhook'])->name('webhook.delete');
    });

});
