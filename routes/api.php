<?php

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

Route::prefix('v1')->namespace('Api\V1')->group(function () {
    Route::middleware(['auth:api', 'verified'])->group(function () {
        // Comments
        Route::apiResource('comments', 'CommentController')->only('destroy');
        Route::apiResource('posts.comments', 'PostCommentController')->only('store');

        // Posts
        Route::apiResource('posts', 'PostController')->only(['update', 'store', 'destroy']);
        Route::post('/posts/{post}/likes', 'PostLikeController@store')->name('posts.likes.store');
        Route::delete('/posts/{post}/likes', 'PostLikeController@destroy')->name('posts.likes.destroy');

        // Users
        Route::apiResource('users', 'UserController')->only('update');
        // Users
        Route::apiResource('users', 'UserController')->only(['index', 'show']);

        // Media
        Route::apiResource('media', 'MediaController')->only(['store', 'destroy']);

        //Проекты
        Route::apiResource('project', 'Project\ProjectController')->only(['index', 'store', 'update', 'destroy']);
        Route::get('project/{project}/journal', 'Project\ProjectController@journal')->name('project.journal');
        Route::get('project/{project}/settings_basic', 'Project\ProjectController@settings_basic')->name('project.settings-basic');
        Route::get('project/{project}/settings_sync', 'Project\ProjectController@settings_sync')->name('project.settings-sync');

        //Хосты
        Route::apiResource('project/{project}/hosts', 'Project\HostController')->only(['index', 'store', 'destroy']);

        //E-mail
        Route::apiResource('project/{project}/emails', 'Project\EmailController')->only(['index', 'store', 'destroy']);

        //Вебхуки
        Route::apiResource('project/{project}/webhooks', 'Project\WebhookController')->only(['index', 'store', 'destroy']);
    });

    Route::post('/lead.add', 'LeadsController@store')->name('lead.store');

    //Route::post('/authenticate', 'Auth\AuthenticateController@authenticate')->name('authenticate');

    // Comments
    //Route::apiResource('posts.comments', 'PostCommentController')->only('index');
    //Route::apiResource('users.comments', 'UserCommentController')->only('index');
    //Route::apiResource('comments', 'CommentController')->only(['index', 'show']);

    // Posts
    //Route::apiResource('posts', 'PostController')->only(['index', 'show']);
    //Route::apiResource('users.posts', 'UserPostController')->only('index');


    // Media
    //Route::apiResource('media', 'MediaController')->only('index');
});


Route::fallback(function () {
    return response()->json(['message' => 'Not Found.'], 404);
});
