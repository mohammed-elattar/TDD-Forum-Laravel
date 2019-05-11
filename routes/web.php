<?php

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
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get("/threads","ThreadsController@index")->name('threads');
Route::get("/threads/create","ThreadsController@create");
Route::post("/threads","ThreadsController@store")->middleware('must-be-confirmed');
Route::get("/threads/{channel}/{thread}","ThreadsController@show");
Route::patch("/threads/{channel}/{thread}","ThreadsController@update");

Route::post("/locked-threads/{thread}","lockedThreadsController@store")->name('locked-threads.store')->middleware('admin');
Route::delete("/locked-threads/{thread}","lockedThreadsController@destroy")->name('locked-threads.destroy')->middleware('admin');

Route::patch("/threads/{channel}/{thread}","ThreadsController@update")->name('threads.update');
Route::delete("/threads/{channel}/{thread}","ThreadsController@destroy");
Route::post("/threads/{channel}/{thread}/subscriptions","ThreadSubscriptionsController@store")->middleware('auth');
Route::delete("/threads/{channel}/{thread}/subscriptions","ThreadSubscriptionsController@destroy")->middleware('auth');

//Route::resource('threads','ThreadsController');
Route::get("/threads/{channel}","ThreadsController@index");
Route::post("/threads/{channel}/{thread}/replies","RepliesController@store");
Route::get("/threads/{channel}/{thread}/replies","RepliesController@index");
Route::delete("/replies/{reply}","RepliesController@destroy")->name('replies.destroy');
Route::patch("/replies/{reply}","RepliesController@update");

Route::post("/replies/{reply}/best","BestRepliesController@store")->name('best-replies.store');

Route::post("/replies/{reply}/favourites","FavouritesController@store");
Route::delete("/replies/{reply}/favourites","FavouritesController@destroy");
Route::get("/profiles/{user}","ProfilesController@show")->name('profile');
Route::delete("/profiles/{user}/notifications/{notificationId}","UserNotificationsController@destroy");
Route::get("/profiles/{user}/notifications","UserNotificationsController@index");

Route::get("/register/confirm","Auth\RegisterConfirmationController@index")->name('rgister.confirm');

Route::get("/api/users","Api\UsersController@index");
Route::post("/api/users/{user}/avatar","Api\UserAvatarController@store")->middleware('auth')->name('avatar');
