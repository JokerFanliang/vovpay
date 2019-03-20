<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->index()->comment('商户id');
            $table->string('merchant',10)->comment('商户号');
            $table->unsignedInteger('agent_id')->index()->default(0)->comment('归属代理：0没有代理');
            $table->unsignedInteger('channel_id')->comment('通道id');
            $table->unsignedInteger('channel_payment_id')->comment('支付方式id');
            $table->string('account',255)->default('account')->comment('账号');
            $table->string('phone_id',100)->default(0)->comment('设备号');
            $table->unsignedInteger('phone_uid')->default(0)->comment('设备归属商户id');
            $table->string('orderMk',4)->default('0000')->comment('固码备注');
            $table->char('orderNo',30)->unique()->comment('系统订单号');
            $table->string('underOrderNo',50)->comment('下游订单号');
            $table->string('onOrderNo')->default(0)->comment('上游订单号');
            $table->decimal('amount',9,2)->default(0)->comment('订单金额');
            $table->decimal('orderRate',7,2)->default(0)->comment('订单手续费');
            $table->decimal('sysAmount',7,2)->default(0)->comment('系统收入');
            $table->decimal('agentAmount',7,2)->default(0)->comment('代理收入');
            $table->decimal('userAmount',7,2)->default(0)->comment('用户收入');
            $table->string('notifyUrl')->comment('商户异步通知地址');
            $table->string('successUrl')->comment('商户同步通知地址');
            $table->string('channelName')->comment('通道名称');
            $table->string('paymentName')->comment('支付方式名称');
            $table->string('extend',1000)->default('{}')->comment('扩展字段，存储json');
            $table->unsignedTinyInteger('status')->default(0)->comment('订单状态：0未支付，1支付成功，2支付异常');
            $table->timestamps();
        });

        DB::statement("ALTER TABLE `pay_orders` comment '订单表'");
        DB::statement("ALTER TABLE `pay_orders` ENGINE=InnoDB");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
