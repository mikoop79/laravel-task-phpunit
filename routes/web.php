<?php

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/

Auth::routes();

/* 
|--------------------------------------------------------------------------
| Task Routes
|--------------------------------------------------------------------------
*/

Route::get('/tasks', 'TaskController@index');
Route::post('/task', 'TaskController@store');
Route::delete('/task/{task}', 'TaskController@destroy');

Route::get('/home', 'HomeController@index')->name('home');
