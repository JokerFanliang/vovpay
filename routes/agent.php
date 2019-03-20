<?php
/**
 * 代理商路由
 * Created by PhpStorm.
 * User: Admin
 * Date: 2018/7/17
 * Time: 17:39
 */

//Auth::routes();
Route::group([], function ($router) {

    Route::get('login', 'LoginController@show')->name('agent.login');
    Route::post('login', 'LoginController@login')->name('agent.login');
    Route::get('signOut', 'LoginController@destroy')->name('agent.signOut');
    Route::get('hasGoogleKey','UserController@hasGoogleKey')->name('agent.hasGoogle');

    Route::group(['middleware' => 'auth:agent'], function () {

        Route::get('/', 'IndexController@show')->name('agent');
        Route::get('extension', 'IndexController@extension')->name('agent.extension');
        Route::get('rate', 'IndexController@rate')->name('agent.rate');
        Route::post('editPassword', 'IndexController@editPassword')->name('agent.editPassword');

        Route::get('user', 'UserController@index')->name('agent.user');
        Route::get('user/show', 'UserController@show')->name('agent.show');
        Route::post('user', 'UserController@add')->name('agent.add');
        Route::post('user/check', 'UserController@checkUnique')->name('agent.check');
        Route::post('user/saveStatus', 'UserController@saveStatus')->name('agent.saveStatus');


        Route::get('info', 'AgentController@index')->name('agent.info');

        Route::get('order', 'OrderController@index')->name('agent.order');
        Route::get('user/order/{id}', 'OrderController@userOrder')->name('agent.userOrder');
        Route::get('order/{id}/show', 'OrderController@show')->name('order.show');
        Route::post('order/saveStatus','OrderController@saveStatus')->name('order.saveStatus');
        Route::post('order/reissue','OrderController@reissue')->name('order.reissue');


        Route::get('bankCard', 'BankCardController@index')->name('agent.bankCard');
        Route::post('bankCard','BankCardController@store')->name('agent.store');
        Route::get('bankCard/{id}/edit','BankCardController@edit')->name('agent.edit');
        Route::post('bankCard/saveStatus','BankCardController@saveStatus')->name('agent.saveStatus');
        Route::delete('bankCard','BankCardController@destroy')->name('agent.destroy');
        Route::post('bankCard/check','BankCardController@checkUnique')->name('agent.bankCheck');

        //提现
        Route::any('withdraws','WithdrawsController@index')->name('agent.withdraws');
        Route::get('withdraws/clearing','WithdrawsController@clearing')->name('agent.clearing');
        Route::post('withdraws/apply','WithdrawsController@store')->name('agent.apply');
        //API管理
        Route::get('api','IndexController@api')->name('agent.api');
        //结算管理
        Route::get('withdraws/manage','WithdrawsController@manage')->name('agent.manageWithdraws');
        Route::get('withdraws/{id}/manage', 'WithdrawsController@doManage')->name('withdraws.manage');
        Route::delete('withdraws','WithdrawsController@destroy')->name('withdraws.destroy');
        Route::post('withdraws/update','WithdrawsController@update')->name('withdraws.update');
        //账号管理
        Route::get('account/{type}','AccountPhoneController@index')->name('agent.account');
        Route::post('account','AccountPhoneController@store')->name('agent.accountAdd');
        Route::post('account/saveStatus','AccountPhoneController@saveStatus')->name('agent.accountStatus');
        Route::get('account/{id}/edit','AccountPhoneController@edit')->name('agent.accountEdit');
        Route::delete('account','AccountPhoneController@destroy')->name('agent.accountDel');
        Route::post('account/check','AccountPhoneController@checkUnique')->name('agent.check');
        //银行卡账号
        Route::post('accountBank','AccountBankCardsController@store')->name('agent.accountBankAdd');
        Route::get('accountBank/{type}','AccountBankCardsController@index')->name('agent.accountBank');
        Route::get('accountBank/{id}/edit','AccountBankCardsController@edit')->name('agent.accountBankEdit');
        Route::post('accountBank/saveStatus','AccountBankCardsController@saveStatus')->name('agent.accountBankStatus');
        Route::delete('accountBank','AccountBankCardsController@destroy')->name('agent.accountBankDel');
        Route::post('accountBank/checkBank','AccountBankCardsController@checkUnique')->name('agent.checkBank');

        //会员通道设置
        Route::get('user/{id}/channel', 'UserController@channel')->name('agent.userChannel');
        Route::post('user/{id}/saveUserRate', 'UserController@saveUserRateStatus')->name('agent.saveUserRate');
        Route::get('user/{id}/rate','UserController@getUserRate')->name('agent.userRate');
        Route::post('user/{id}/userRateStore', 'UserController@userRateStore')->name('agent.userRateStore');

        //修改支付密码
        Route::post('editPaypwd','IndexController@editpaypwd')->name('agent.editPaypwd');
        //安全设置
        Route::get('validator','ValidatorController@index')->name('agent.validator');
        Route::post('validator','ValidatorController@store')->name('agent.validator');
    });

});