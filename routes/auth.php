<?php

use App\Http\Controllers\ProjectController;
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
        Route::get('account', 'UserController@edit')->name('users.edit');
        Route::match(['put', 'patch'], 'account', 'UserController@update')->name('users.update');

        Route::get('password', 'UserPasswordController@edit')->name('users.password');
        Route::match(['put', 'patch'], 'password', 'UserPasswordController@update')->name('users.password.update');

        Route::get('token', 'UserTokenController@edit')->name('users.token');
        Route::match(['put', 'patch'], 'token', 'UserTokenController@update')->name('users.token.update');
    });

//    Route::prefix('project')->group(function () {
//        Route::get('create', [ProjectController::class, 'create'])->name('create');
//        Route::get('{id}', [ProjectController::class, 'show']);
//    });

    Route::resource('project', 'ProjectController');

    Route::resource('newsletter-subscriptions', 'NewsletterSubscriptionController')->only('store');
});
