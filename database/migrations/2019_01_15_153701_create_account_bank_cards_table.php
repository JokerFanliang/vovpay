<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateAccountBankCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_bank_cards', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->comment('商户id');
            $table->unsignedInteger('third')->default(0)->comment('第三方挂号，0-用户 1-第三方');
            $table->unsignedInteger('channel_payment_id')->comment('支付方式id');
            $table->string('accountType')->nullable()->comment('账户类型：支付宝、微信等等');
            $table->unsignedInteger('dayQuota')->default(0)->comment('单日限额: 0不限额');
            $table->string('phone_id')->comment('手机标识');
            $table->string('bank_name')->comment('银行名称');
            $table->string('bank_mark')->comment('银行缩写');
            $table->string('bank_account')->comment('持卡人');
            $table->string('cardNo')->comment('银行卡账号');
            $table->decimal('tradeAmount')->default(0)->comment('交易成功金额');
            $table->unsignedTinyInteger('status')->default(0)->comment('状态：0禁用。1启用');
            $table->unsignedTinyInteger('valid')->default(1)->comment('有效标识：0-无效；1-有效；默认1');
            $table->string('signKey')->comment('密钥与手机通讯时使用');
            $table->timestamps();
            $table->string('chard_index')->nullable()->default(0)->comment('银行卡在支付宝里的id');
            $table->string('qrcode')->nullable()->default(0)->comment('银行任意金额码链接');
        });

        DB::statement("ALTER TABLE `pay_account_bank_cards` comment '银行卡实时码配置表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('account_bank_cards');
    }
}
