<?php

use App\Http\Controllers\Project\TelegramIDController;
use App\Mail\Newsletter;
use App\Models\Post;
use Illuminate\Support\Facades\Route;

use Carbon\Carbon;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

//Route::get('/', 'PostController@index')->name('home');
//Route::get('/posts/feed', 'PostFeedController@index')->name('posts.feed');
//Route::resource('posts', 'PostController')->only('show');
//Route::resource('users', 'UserController')->only('show');

//Route::get('/test', function (){
//    $posts = Post::lastMonth()->get();
//    Mail::to('gorin163@gmail.com')->send(new Newsletter($posts,'gorin163@gmail.com'));
//});

//Route::get('newsletter-subscriptions/unsubscribe', 'NewsletterSubscriptionController@unsubscribe')->name('newsletter-subscriptions.unsubscribe');
Route::get('/test', [\App\Http\Controllers\Project\WebhookController::class , 'test']);

Route::post('telegram/webhook', [TelegramIDController::class, 'webhook'])->name('telegram.webhook');
//Route::resource('log', LogController::class)->only(['index', 'store', 'destroy']);

//Route::get('/test-mail', function () {
//    $invoice = \App\Models\Leads::findOrFail(4);
//
//    return new \App\Mail\Leads\SendLeadData($invoice,'');
//});
