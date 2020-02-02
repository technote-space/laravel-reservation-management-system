<?php

use Illuminate\Routing\Router;

Route::post('login', 'Auth\LoginController@login')->name('login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');
Route::get('user', 'IndexController@user')->name('user');

Route::group([
    'middleware' => ['auth'],
], function (Router $router) {
    $router->get('summary', 'Api\SummaryController@index')->name('summary');
    $router->get('checkout', 'Api\ReservationController@checkoutList')->name('reservation.checkout.list');
    $router->patch('checkin', 'Api\ReservationController@checkin')->name('reservation.checkin');
    $router->patch('checkout', 'Api\ReservationController@checkout')->name('reservation.checkout');
    $router->patch('cancel', 'Api\ReservationController@cancel')->name('reservation.cancel');
});
