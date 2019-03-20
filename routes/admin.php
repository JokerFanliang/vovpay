<?php
/**
 * 总后台路由
 * Created by PhpStorm.
 * User: Admin
 * Date: 2018/7/17
 * Time: 17:39
 */

//Auth::routes();
Route::group([], function () {
    Route::get('login', 'LoginController@show')->name('admin.login');
    Route::post('login', 'LoginController@login')->name('admin.login');
    Route::get('dropout', 'LoginController@destroy')->name('admin.dropout');
    Route::get('hasGoogleKey','AdminsController@hasGoogleKey')->name('admin.hasGoogle');
    Route::group(['middleware' => 'auth:admin'], function () {
        Route::get('/', 'IndexController@index')->name('admin');
        Route::get('main', 'IndexController@main')->name('admin.main');
        //菜单
        Route::get('rules','RulesController@index')->name('rules.index');
        Route::post('rules','RulesController@store')->name('rules.store');
        Route::get('rules/{id}/edit', 'RulesController@edit')->name('rules.edit');
        Route::delete('rules','RulesController@destroy')->name('rules.destroy');
        Route::post('rules/saveCheck','RulesController@saveCheck')->name('rules.saveCheck');
        //角色
        Route::get('roles','RolesController@index')->name('roles.index');
        Route::post('roles','RolesController@store')->name('roles.store');
        Route::get('roles/{id}/edit', 'RolesController@edit')->name('roles.edit');
        Route::delete('roles','RolesController@destroy')->name('roles.destroy');
        //权限分配
        Route::get('roles/{role_id}/rules','RolesController@getRules')->name('getRules');
        Route::put('roles/{role_id}/rules','RolesController@storeRules')->name('setRules');
        //管理员
        Route::get('admins','AdminsController@index')->name('admins.index');
        Route::post('admins','AdminsController@store')->name('admins.store');
        Route::get('admins/{id}/edit', 'AdminsController@edit')->name('admins.edit');
        Route::delete('admins','AdminsController@destroy')->name('admins.destroy');
        Route::post('admins/saveStatus','AdminsController@saveStatus')->name('admins.saveStatus');
        Route::post('admins/check','AdminsController@checkUnique')->name('admins.check');
        //会员管理
        Route::get('users/{type}','UsersController@index')->name('users.index');
        Route::post('users','UsersController@store')->name('users.store');
        Route::get('users/{id}/edit', 'UsersController@edit')->name('users.edit');
        Route::delete('users','UsersController@destroy')->name('users.destroy');
        Route::post('users/saveStatus','UsersController@saveStatus')->name('users.saveStatus');
        Route::post('users/check','UsersController@checkUnique')->name('users.check');
        Route::get('users/{id}/quota','UsersController@quota')->name('users.quota');
        Route::post('users/quota','UsersController@quotaStore')->name('users.quotaStore');
        Route::get('users/{id}/quotaLog','UsersController@quotaLog')->name('users.quotaLog');
        Route::post('users/balance','UsersController@balance')->name('users.balance');

        //会员通道设置
        Route::get('users/{id}/channel', 'UsersController@channel')->name('users.channel');
        Route::post('users/{id}/saveUserRate', 'UsersController@saveUserRateStatus')->name('users.saveUserRate');
        Route::get('users/{id}/rate','UsersController@getUserRate')->name('users.rate');
        Route::post('users/{id}/userRateStore', 'UsersController@userRateStore')->name('users.userRateStore');
        //通道管理
        Route::get('channels','ChannelsController@index')->name('channels.index');
        Route::post('channels','ChannelsController@store')->name('channels.store');
        Route::get('channels/{id}/edit', 'ChannelsController@edit')->name('channels.edit');
        Route::delete('channels','ChannelsController@destroy')->name('channels.destroy');
        Route::post('channels/saveStatus','ChannelsController@saveStatus')->name('channels.saveStatus');
        Route::post('channels/check','ChannelsController@checkUnique')->name('channels.check');
        Route::post('channels/savePlanType','ChannelsController@savePlanType')->name('channels.savePlanType');
        //支付方式管理
        Route::get('channelPayments','ChannelPaymentsController@index')->name('channelPayments.index');
        Route::post('channelPayments','ChannelPaymentsController@store')->name('channelPayments.store');
        Route::get('channelPayments/{id}/edit', 'ChannelPaymentsController@edit')->name('channelPayments.edit');
        Route::delete('channelPayments','ChannelPaymentsController@destroy')->name('channelPayments.destroy');
        Route::post('channelPayments/saveStatus','ChannelPaymentsController@saveStatus')->name('channelPayments.saveStatus');
        Route::post('channelPayments/check','ChannelPaymentsController@checkUnique')->name('channelPayments.check');
        Route::post('channelPayments/savePlanType','ChannelPaymentsController@savePlanType')->name('channelPayments.savePlanType');
        Route::post('channelPayments/upload','ChannelPaymentsController@upload')->name('channelPayments.upload');
        //订单管理
        Route::get('orders','OrdersController@index')->name('orders.index');
        Route::get('orders/{id}/show', 'OrdersController@show')->name('orders.show');
        Route::delete('orders','OrdersController@destroy')->name('orders.destroy');
        Route::post('orders/saveStatus','OrdersController@saveStatus')->name('orders.saveStatus');
        Route::post('orders/reissue','OrdersController@reissue')->name('orders.reissue');
        //结算管理
        Route::get('withdraws','WithdrawsController@index')->name('withdraws.index');
        Route::get('withdraws/{id}/manage', 'WithdrawsController@manage')->name('withdraw.manage');
        Route::delete('withdraws','WithdrawsController@destroy')->name('withdraw.destroy');
        Route::post('withdraws/saveStatus','WithdrawsController@saveStatus')->name('withdraw.saveStatus');
        Route::post('withdraws/update','WithdrawsController@update')->name('withdraw.update');
        //账号管理
        Route::get('account/{type}','AccountPhoneController@index')->name('admin.account');
        Route::post('account','AccountPhoneController@store')->name('admin.accountAdd');
        Route::post('account/saveStatus','AccountPhoneController@saveStatus')->name('admin.accountStatus');
        Route::get('account/{id}/edit','AccountPhoneController@edit')->name('admin.accountEdit');
        Route::delete('account','AccountPhoneController@destroy')->name('admin.accountDel');
        Route::post('account/check','AccountPhoneController@checkUnique')->name('admin.check');
        //银行卡账号
        Route::get('accountBank/{type}','AccountBankCardsController@index')->name('admin.accountBank');
        Route::post('accountBank','AccountBankCardsController@store')->name('admin.accountBankAdd');
        Route::get('accountBank/{id}/edit','AccountBankCardsController@edit')->name('admin.accountBankEdit');
        Route::post('accountBank/saveStatus','AccountBankCardsController@saveStatus')->name('admin.accountBankStatus');
        Route::delete('accountBank','AccountBankCardsController@destroy')->name('admin.accountBankDel');
        Route::post('accountBank/checkBank','AccountBankCardsController@checkUnique')->name('admin.checkBank');


        //系统配置
        Route::get('system','SystemController@index')->name('system.index');
        Route::get('system/{id}/edit', 'SystemController@edit')->name('system.edit');
        Route::post('system','SystemController@store')->name('system.store');
        //账号状态（不是总后台挂号时，显示账号状态）
        Route::get('accountall','AccountListController@index')->name('account.all');
        Route::post('accountall/saveAllStatus','AccountListController@saveAllStatus')->name('account.saveAllStatus');
        //修改密码
        Route::post('editpwd','IndexController@editpwd')->name('admin.editpwd');
        //安全设置
        Route::get('validator','ValidatorController@index')->name('admin.validator');
        Route::post('validator','ValidatorController@store')->name('admin.validator');

        Route::post('withdraws/count','WithdrawsController@checkNotice')->name('withdraw.checkNotice');
    });
});