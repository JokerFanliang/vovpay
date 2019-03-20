<?php
/**
 * 商户路由
 * Created by PhpStorm.
 * User: Admin
 * Date: 2018/7/17
 * Time: 17:39
 */

//Auth::routes();
Route::group([], function ($router) {
    Route::get('login','LoginController@show')->name('user.login');
    Route::post('login','LoginController@login')->name('user.login');
    Route::get('registerShow','LoginController@registerShow')->name('user.registerShow');
    Route::post('register','LoginController@register')->name('user.register');
    Route::get('dropout', 'LoginController@destroy')->name('user.dropout');
    Route::get('hasGoogleKey','LoginController@hasGoogleKey')->name('user.hasGoogle');

    Route::group(['middleware' => 'auth:user'], function () {
        //用户管理，密码
        Route::get('/', 'IndexController@index')->name('user');
        Route::get('index', 'IndexController@show')->name('user.show');
        Route::any('index/user', 'IndexController@user')->name('user.user');
        Route::post('index/editPassword', 'IndexController@editPassword')->name('user.editPassword');
        Route::get('index/doc/pay','IndexController@pay')->name('user.pay');
        Route::get('index/doc/withdraw','IndexController@withdraw')->name('user.withdraw');

        Route::get('index/validator','IndexController@validator')->name('user.validator');
        Route::get('index/api','IndexController@api')->name('user.api');
        //订单
        Route::get('order','OrderController@index')->name('user.order');
        Route::get('order/{id}/show', 'OrderController@show')->name('order.show');
        Route::get('order/invoice','OrderController@invoice')->name('user.invoice');
        Route::get('order/recharge','OrderController@recharge')->name('user.recharge');
        //银行卡管理
        Route::post('bankCard/store','BankCardController@store')->name('user.store');
        Route::get('bankCard','BankCardController@bankCard')->name('user.bankCard');
        Route::delete('bankCard','BankCardController@destroy')->name('user.destroy');
        Route::get('bankCard/{id}/edit','BankCardController@edit')->name('user.edit');
        Route::post('bankCard/saveStatus','BankCardController@saveStatus')->name('user.saveStatus');
        Route::post('account/bankCheck','BankCardController@checkUnique')->name('user.bankCheck');
        //提现
        Route::any('withdraws','WithdrawsController@index')->name('user.withdraws');
        Route::get('withdraws','WithdrawsController@clearing')->name('user.clearing');
        Route::post('withdraws/store','WithdrawsController@store')->name('user.apply');
        //账号管理
        Route::get('account/{type}','AccountPhoneController@index')->name('user.account');
        Route::post('account','AccountPhoneController@store')->name('user.accountAdd');
        Route::post('account/saveStatus','AccountPhoneController@saveStatus')->name('user.accountStatus');
        Route::get('account/{id}/edit','AccountPhoneController@edit')->name('user.accountEdit');
        Route::delete('account','AccountPhoneController@destroy')->name('user.accountDel');
        Route::post('account/check','AccountPhoneController@checkUnique')->name('user.check');
        //银行卡账号
        Route::post('accountBank','AccountBankCardsController@store')->name('user.accountBankAdd');
        Route::get('accountBank/{type}','AccountBankCardsController@index')->name('user.accountBank');
        Route::get('accountBank/{id}/edit','AccountBankCardsController@edit')->name('user.accountBankEdit');
        Route::post('accountBank/saveStatus','AccountBankCardsController@saveStatus')->name('user.accountBankStatus');
        Route::delete('accountBank','AccountBankCardsController@destroy')->name('user.accountBankDel');
        Route::post('accountBank/checkBank','AccountBankCardsController@checkUnique')->name('user.checkBank');

        //修改支付密码
        Route::post('editPaypwd','IndexController@editpaypwd')->name('user.editPaypwd');
        //安全设置
        Route::get('validator','ValidatorController@index')->name('user.validator');
        Route::post('validator','ValidatorController@store')->name('user.validator');
    });

});