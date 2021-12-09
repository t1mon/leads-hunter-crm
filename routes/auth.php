<?php

use App\Http\Controllers\NewsletterSubscriptionController;
use App\Http\Controllers\Project\EmailController;
use App\Http\Controllers\Project\TelegramIDController;
use App\Http\Controllers\Project\HostController;
use App\Http\Controllers\Project\ProjectController;
use App\Http\Controllers\Project\LeadClassController;
use App\Http\Controllers\Project\UserPermissionsController;
use App\Http\Controllers\Project\ProjectTokenController;
use App\Http\Controllers\Project\WebhookController;
use App\Http\Controllers\Project\Lead\CommentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserPasswordController;
use App\Http\Controllers\UserTokenController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::redirect('/.well-known/change-password', '/settings/password');

Auth::routes(['verify' => true]);

Route::prefix('auth')->group(function () {
    Route::get('{provider}', 'Auth\AuthController@redirectToProvider')->name('auth.provider');
    Route::get('{provider}/callback', 'Auth\AuthController@handleProviderCallback');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::prefix('settings')->group(function () {
        Route::get('account', [ UserController::class,'edit' ])->name('users.edit');
        Route::match(['put', 'patch'], 'account', [ UserController::class,'update' ])->name('users.update');

        Route::get('password', [ UserPasswordController::class,'edit' ])->name('users.password');
        Route::match(['put', 'patch'], 'password', [ UserPasswordController::class,'update' ])->name('users.password.update');

        Route::get('token', [ UserTokenController::class,'edit' ])->name('users.token');
        Route::match(['put', 'patch'], 'token', [ UserTokenController::class,'update' ])->name('users.token.update');
    });


    Route::get('/', [ ProjectController::class, 'index' ])->name('home');
    Route::resource('project', ProjectController::class)->only(['index','create', 'store', 'update', 'destroy']);
    Route::prefix('project')->group(function () {
        Route::get('{project}/journal', [ProjectController::class, 'journal'])->name('project.journal');
        Route::get('{project}/journal/download', [ProjectController::class, 'journal_export'])->name('project.journal.download');

        //TODO При переделке фронта переделать или удалить этот маршрут
        Route::post('{project}/journal/{lead}/class/assign', [LeadClassController::class, 'assign'])->name('class-assign');

        Route::get('{project}/test', [ProjectController::class, 'test'])->name('project.test');

        Route::get('{project}/log', [ProjectController::class, 'log'])->name('project.log');
        Route::get('{project}/log-export', [ProjectController::class, 'log_export'])->name('project.log-export');

        Route::get('{project}/settings_basic/{tab?}', [ProjectController::class, 'settings_basic'])->name('project.settings-basic');
        Route::get('{project}/settings_sync/{tab?}', [ProjectController::class, 'settings_sync'])->name('project.settings-sync');
        Route::get('{project}/hosts', [ProjectController::class, 'hosts'])->name('project.hosts');
        Route::get('{project}/users', [UserPermissionsController::class, 'list'])->name('project.users');
        Route::post('{project}/webhook/{webhook}/toggle', [WebhookController::class, 'toggle'])->name('webhook.toggle');
        Route::get('{project}/notification', [ProjectController::class, 'notification'])->name('project.notification');
        Route::get('{project}/token', [ProjectTokenController::class, 'edit'])->name('project.token');
        Route::match(['put', 'patch'], '{project}/token', [ProjectTokenController::class, 'update'])->name('project.token.update');
        Route::resource('{project}/host', HostController::class)->only(['store', 'destroy']);
        Route::resource('{project}/user', UserPermissionsController::class)->only(['store', 'update', 'destroy']);
        Route::resource('project/{project}/email', EmailController::class)->only(['store', 'destroy']);
        Route::resource('project/{project}/{lead}/comment', CommentController::class)->only(['show', 'create', 'store', 'edit', 'destroy']);
        Route::resource('project/{project}/telegram', TelegramIDController::class)->only(['store', 'destroy']);
        Route::resource('project/{project}/class', LeadClassController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
        Route::resource('project/{project}/webhook', WebhookController::class)->only(['store', 'edit', 'update', 'destroy']);
    });

    Route::resource('newsletter-subscriptions', NewsletterSubscriptionController::class)->only('store');
});

Route::get('/test', [WebhookController::class, 'test'])->name('test');