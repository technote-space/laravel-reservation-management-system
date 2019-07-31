<?php

use Illuminate\Routing\Router;

Route::post('login', 'Auth\LoginController@login')->name('login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');
Route::get('user', 'IndexController@user')->name('user');

Route::group([
    'middleware' => ['auth'],
], function (Router $router) {
    $router->apiResources([
        'guests'       => 'Api\GuestController',
        'rooms'        => 'Api\RoomController',
        'reservations' => 'Api\ReservationController',
    ]);
});
