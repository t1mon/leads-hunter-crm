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
        Route::post('project/leads-count', 'Project\ProjectController@leadsCountCollection')->name('project.leads.count');
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

    Route::post('vk/project/{project}/webhook', 'Project\VKFormController@webHook')->name('vk.webhook'); //Добавление лида через VK API

    //Route::post('/authenticate', 'Auth\AuthenticateController@authenticate')->name('authenticate');
//Проекты
    //Route::apiResource('comments', 'CommentController')->only(['index', 'show']);

    // Posts
    //Route::apiResource('posts', 'PostController')->only(['index', 'show']);
    //Route::apiResource('users.posts', 'UserPostController')->only('index');


    // Media
    //Route::apiResource('media', 'MediaController')->only('index');
});

Route::prefix('v2')->name('v2.')->group(function(){
    Route::middleware(['auth:api', 'verified'])->group(function (){
        Route::get('dashboard', [ \App\Http\Controllers\Api\V2\Project\ProjectController::class, 'index'])->name('dashboard');

        //Лиды
        Route::prefix('lead')->name('lead.')->group(function(){
            //Принять лид
            Route::prefix('accept')->name('accept')->group(function(){
                Route::post('assign', [\App\Http\Controllers\Api\V2\Lead\AcceptLeadController::class, 'assign'])->name('assign');
                Route::delete('dismiss', [\App\Http\Controllers\Api\V2\Lead\AcceptLeadController::class, 'dismiss'])->name('dismiss');
                Route::get('users', [\App\Http\Controllers\Api\V2\Lead\AcceptLeadController::class, 'getUsersForProject'])->name('users');
            });
            //Управление лидами вручную
            Route::post('add', [\App\Http\Controllers\Api\V2\Lead\LeadController::class, 'store'])->name('add');
            Route::delete('delete', [\App\Http\Controllers\Api\V2\Lead\LeadController::class, 'destroy'])->name('delete');

            //Дата следующего звонка
            Route::prefix('nextcall')->name('nextcall.')->group(function(){
                Route::post('add', [\App\Http\Controllers\Api\V2\LeadController::class, 'addNextcall'])->name('add');
                Route::delete('clear', [\App\Http\Controllers\Api\V2\LeadController::class, 'clearNextcall'])->name('clear');
            });

            //Регион, выставляемый вручную
            Route::prefix('manual_region')->name('manual_region.')->group(function(){
                Route::post('add', [\App\Http\Controllers\Api\V2\Lead\ManualRegionController::class, 'store'])->name('add');
                Route::delete('clear', [\App\Http\Controllers\Api\V2\Lead\ManualRegionController::class, 'destroy'])->name('clear');
            });

            //Компания
            Route::prefix('company')->name('company.')->group(function(){
                Route::post('add', [\App\Http\Controllers\Api\V2\Lead\CompanyController::class, 'store'])->name('add');
                Route::delete('clear', [\App\Http\Controllers\Api\V2\Lead\CompanyController::class, 'destroy'])->name('clear');
            });
        });
        
        //Проекты
        Route::prefix('project')->name('project.')->group(function(){
            //Полномочия пользователя
            Route::prefix('permissions')->name('permissions.')->group(function(){
                Route::get('index', [\App\Http\Controllers\Api\V2\Project\UserPermissionsController::class, 'index'])->name('index');
                Route::post('assign', [\App\Http\Controllers\Api\V2\Project\UserPermissionsController::class, 'assign'])->name('assign');
                Route::put('change', [\App\Http\Controllers\Api\V2\Project\UserPermissionsController::class, 'changeRole'])->name('change');
                Route::delete('dismiss', [\App\Http\Controllers\Api\V2\Project\UserPermissionsController::class, 'dismiss'])->name('dismiss');
            });

            Route::get('{project}/journal', [\App\Http\Controllers\Api\V2\Project\ProjectController::class, 'journal'] )->name('journal');
            Route::get('{project}/journal/variants', [\App\Http\Controllers\Api\V2\Project\ProjectController::class, 'getFilterVariants'] )->name('journal.variants');
            Route::get('{project}/export', [\App\Http\Controllers\Api\V2\Project\ProjectController::class, 'export'])->name('export');

        });

        //Комментарии
        Route::prefix('comment')->name('comment.')->group(function(){
            Route::post('add', [\App\Http\Controllers\Api\V2\Project\Lead\CommentController::class, 'store'])->name('add');
            Route::get('show', [\App\Http\Controllers\Api\V2\Project\Lead\CommentController::class, 'show'])->name('show');
            Route::delete('delete', [\App\Http\Controllers\Api\V2\Project\Lead\CommentController::class, 'delete'])->name('delete');
        });
    });
});


Route::fallback(function () {
    return response()->json(['message' => 'Not Found.'], 404);
});
