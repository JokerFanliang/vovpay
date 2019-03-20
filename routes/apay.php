<?php

Route::group([], function () {
    Route::post('appRegister', 'PhoneLoginController@login'); // 收款助手登录
});