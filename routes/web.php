<?php

Route::get('/{any?}', 'IndexController@index')->where('any', '.+');
