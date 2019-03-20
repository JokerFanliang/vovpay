<?php

Route::group([], function () {
    Route::post('/', 'PayController@index')->name('pay');
    Route::get('query', 'PayController@queryOrder')->name('pay.query');
    Route::get('success/{action}', 'PayController@successCallback')->name('pay.success');
    Route::post('notify/{action}', 'PayController@notifyCallback')->name('pay.notify');
    Route::get('demo','PayController@demo')->name('pay.demo');
    Route::post('demo','PayController@demoStore')->name('pay.demo');
    Route::get('h5pay/{orderNo}','PayH5Controller@h5pay')->name('pay.h5pay');
});