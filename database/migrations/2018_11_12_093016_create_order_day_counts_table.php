<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateOrderDayCountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_day_counts', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->comment('用户id');
            $table->unsignedInteger('agent_id')->comment('代理id');
            $table->decimal('merchant_amount',12,2)->default(0)->comment('商户单日订单成功金额');
            $table->decimal('merchant_income',12,2)->default(0)->comment('商户单日实际收入');
            $table->unsignedInteger('merchant_order_count')->default(0)->comment('商户单日订单量');
            $table->unsignedInteger('merchant_order_suc_count')->default(0)->comment('商户单日成功订单量');
            $table->decimal('sys_amount',12,2)->default(0)->comment('平台单日订单成功金额');
            $table->decimal('sys_income',12,2)->default(0)->comment('平台单日收入金额');
            $table->unsignedInteger('sys_order_count')->default(0)->comment('平台单日订单总数量');
            $table->unsignedInteger('sys_order_suc_count')->default(0)->comment('平台单日成功订单数量');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
        DB::statement("ALTER TABLE `pay_order_day_counts` comment '单日订单统计表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_day_counts');
    }
}
