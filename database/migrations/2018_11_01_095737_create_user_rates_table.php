<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateUserRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_rates', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->comment('用户id');
            $table->unsignedInteger('channel_id')->comment('通道id');
            $table->unsignedInteger('channel_payment_id')->comment('支付id');
            $table->decimal('rate',9,6)->default(0)->comment('商户费率：为0时走通道运营费率');
            $table->unsignedTinyInteger('status')->default(0)->comment('状态：0禁用。1启用');
        });
        DB::statement("ALTER TABLE `pay_user_rates` comment '商户费率表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_rates');
    }
}
