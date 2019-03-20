<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateWithdrawsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('withdraws', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->comment('商户id');
            $table->unsignedInteger('agent_id')->comment('商户所属代理id');
            $table->string('bankName',40)->comment('银行名称');
            $table->decimal('withdrawAmount',11,2)->default(0)->comment('提现金额');
            $table->decimal('withdrawRate',6,2)->default(0)->comment('提现手续费');
            $table->decimal('toAmount',11,2)->default(0)->comment('到账金额');
            $table->string('accountName')->comment('银行开户名');
            $table->string('bankCardNo')->comment('银行卡号');
            $table->string('branchName')->comment('银行支行名');
            $table->string('bankCode')->comment('银行编码');
            $table->string('orderId')->unique()->index()->comment('结算单流水号');
            $table->string('outOrderId')->nullable()->index()->comment('商户侧提交结算单号');
            $table->string('upOrderId')->unique()->index()->nullable()->comment('上游结算单号');
            $table->string('channelCode')->comment('结算通道编码');
            $table->unsignedTinyInteger('type')->default(1)->comment('结算类型:1普通结算 2代付结算');
            $table->string('comment')->nullable()->comment('结算备注信息');
            $table->text('extend')->nullable()->comment('扩展银行卡信息,json格式');
            $table->unsignedTinyInteger('status')->default(0)->comment('体现状态：0未处理，1处理中，2已结算，3结算异常,4取消结算');
            $table->timestamps();
        });

        DB::statement("ALTER TABLE `pay_withdraws` comment '提现记录表'");
        DB::statement("ALTER TABLE `pay_withdraws` ENGINE=InnoDB");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('withdraws');
    }
}
