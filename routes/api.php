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

        //Поиск
        Route::get('search', 'SearchController@search')->name('search');

        //Проекты
        Route::apiResource('project', 'Project\ProjectController')->only(['index', 'store', 'update', 'destroy']);
        Route::post('project/leads-count', 'Project\ProjectController@allLeadsCount')->name('project.leads.count');
        Route::get('project/{project}/journal', 'Project\ProjectController@journal')->name('project.journal');
        Route::get('project/{project}/settings_basic', 'Project\ProjectController@settings_basic')->name('project.settings-basic');

        Route::get('project/{project}/settings_sync', 'Project\ProjectController@settings_sync')->name('project.settings-sync');
        Route::get('project/{project}/toggle', 'Project\ProjectController@toggle')->name('project.toggle');
        Route::get('project/{project}/journal/export', 'Project\ProjectController@journalExport')->name('project.journal-export');


        //Разрешения пользователей
        Route::apiResource('project/{project}/users', 'Project\UserPermissionsController')->only(['index', 'store', 'update', 'destroy']);

        //Классы лидов
        Route::apiResource('project/{project}/class', 'Project\Lead\LeadClassController')->only(['store', 'update', 'destroy']);
        Route::post('project/{project}/journal/{lead}/class/assign', 'Project\Lead\LeadClassController@assign')->name('class-assign');

        //Комментарии к лидам
        Route::apiResource('project/{project}/leads/{lead}/comment', 'Project\Lead\CommentController')->only(['show', 'store', 'destroy']);

        //Токен проекта
        Route::apiResource('project/{project}/token', 'Project\ProjectTokenController')->only(['edit', 'update']);

        //Хосты
        Route::apiResource('project/{project}/hosts', 'Project\HostController')->only(['index', 'store', 'destroy']);

        //E-mail
        Route::apiResource('project/{project}/emails', 'Project\EmailController')->only(['index', 'store', 'destroy']);

        //Контакты Telegram
        Route::apiResource('project/{project}/telegram', 'Project\TelegramIDController')->only(['index', 'store', 'destroy']);
        Route::post('telegram/webhook', 'Project\TelegramIDController@webhook')->name('telegram.webhook');

        //Вебхуки
        Route::apiResource('project/{project}/webhooks', 'Project\WebhookController')->only(['index', 'store', 'destroy']);
    });

    Route::post('/lead.add', 'LeadsController@store')->name('lead.store');
    Route::put('/lead.update', 'LeadsController@update')->name('lead.update');
    Route::delete('/lead.destroy', 'LeadsController@destroy')->name('lead.destroy');
    Route::get('/lead.test', 'LeadsController@test')->name('lead.test');
    // Route::apiResource('/lead', 'LeadsController')->only(['store', 'update', 'destroy']);

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
