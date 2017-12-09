<?php

Route::view('/', 'app');
Route::group(['prefix' => 'giphy'], function () {
    Route::get('/random', 'GiphyController@getRandom');
    Route::get('/search', 'GiphyController@getSearch');
});
Route::get('/avatars', 'AvatarController');