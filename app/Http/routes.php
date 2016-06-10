<?php

Route::get('/', ['as' => 'dashboard', 'uses' => 'HomeController@index']);

Route::auth();
Route::get('register/confirm/{token}', 'Auth\ConfirmationController@confirmEmail');
Route::get('/home', 'HomeController@index');

Route::group(
    ['prefix' => 'images'],
    function () {
        Route::get('avatar/{user}', 'ImagesController@avatar');
    }
);

Route::group(
    ['middleware' => 'auth'],
    function () {


        Route::group(
            ['prefix' => 'api', 'namespace' => 'Api'],
            function () {
                Route::get('wall', 'WallsController@home');
                Route::get('wall/{user}', 'WallsController@user');
                Route::group(
                    ['prefix' => 'users'],
                    function () {
                        Route::get('', 'UsersController@autocomplete');
                        Route::put('avatar', 'UsersController@uploadAvatar');
                        Route::post('{user}/follow', 'UsersController@follow');
                        Route::post('{user}/unfollow', 'UsersController@unfollow');

                    }
                );

                Route::group(
                    ['prefix' => 'posts'],
                    function () {
                        Route::post('', 'PostsController@store');
                        Route::patch('{post}', 'PostsController@update');
                        Route::delete('{post}', 'PostsController@destroy');
                        Route::get('{post}/like', 'PostLikesController@like');
                        Route::get('{post}/likes', 'PostLikesController@likes');
                        Route::post('{post}/comment', 'CommentsController@store');
                        Route::get('{post}/comments', 'CommentsController@show');
                    }
                );

                Route::group(
                    ['prefix' => 'comments'],
                    function () {
                        Route::patch('{comment}', 'CommentsController@update');
                        Route::delete('{comment}', 'CommentsController@destroy');
                        Route::get('{comment}/like', 'CommentsController@like');
                        Route::get('{comment}/likes', 'CommentsController@likes');
                    }
                );

                Route::post('follow/{user}', 'FollowsController@follow');
                Route::post('unfollow/{user}', 'FollowsController@unfollow');

            }
        );

        Route::resource('posts', 'PostsController');
        Route::get('profile', 'UsersController@profile');
        Route::post('profile', 'UsersController@updateProfile');
        Route::get('settings', 'UsersController@settings');
        Route::post('settings', 'UsersController@updateSettings');
        Route::get('{username}/photos', 'UsersController@photos');
        Route::get('{username}/following', 'UsersController@following');
        Route::get('{username}/followers', 'UsersController@followers');
        Route::get('{username}', 'UsersController@wall');

    }
);