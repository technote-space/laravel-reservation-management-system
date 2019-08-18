<?php

use Illuminate\Routing\Router;

Route::post('login', 'Auth\LoginController@login')->name('login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');
Route::get('user', 'IndexController@user')->name('user');

Route::group([
    'middleware' => ['auth'],
], function (Router $router) {
    $router->post('reservations/check', 'Api\ReservationController@check')->name('reservations.check');
    $router->get('summary', 'Api\SummaryController@index')->name('summary');
});
