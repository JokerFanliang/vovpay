<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateAccountPhonesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_phones', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->comment('商户id');
            $table->unsignedInteger('third')->default(0)->comment('第三方挂号，0-用户 1-第三方');
            $table->unsignedInteger('channel_payment_id')->comment('支付方式id');
            $table->string('accountType')->nullable()->comment('账户类型：支付宝、微信等等');
            $table->unsignedInteger('dayQuota')->default(0)->comment('单日限额: 0不限额');
            $table->string('phone_id')->comment('手机标识');
            $table->string('alipayuserid')->nullable()->comment('支付宝userid');
            $table->string('alipayusername')->nullable()->comment('支付宝实名');
            $table->string('account')->comment('收款账号');
            $table->string('qrcode')->default('0')->comment('收款任意金额码');
            $table->decimal('tradeAmount')->default(0)->comment('交易成功金额');
            $table->unsignedTinyInteger('status')->default(0)->comment('状态：0禁用。1启用');
            $table->string('signKey')->comment('密钥与手机通讯时使用');
            $table->timestamps();
        });

        DB::statement("ALTER TABLE `pay_account_phones` comment '实时码配置表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('account_phones');
    }
}
