<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateAccountCloudsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_clouds', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->comment('商户id');
            $table->unsignedInteger('channel_payment_id')->comment('支付方式id');
            $table->string('accountType')->nullable()->comment('账户类型：支付宝、微信等等');
            $table->string('account')->comment('账号');
            $table->string('cloudAddress')->comment('云端地址');
            $table->unsignedInteger('dayQuota')->default(0)->comment('单日限额: 0不限额');
            $table->decimal('tradeAmount')->default(0)->comment('交易成功金额');
            $table->string('content')->nullable()->comment('任意金额码内容');
            $table->unsignedTinyInteger('status')->default(0)->comment('状态：0禁用。1启用');
            $table->timestamps();
        });

        DB::statement("ALTER TABLE `pay_account_clouds` comment '云端固码配置表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('account_clouds');
    }
}
