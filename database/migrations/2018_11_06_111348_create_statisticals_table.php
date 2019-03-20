<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateStatisticalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('statisticals', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->unique()->comment('商户id');
            $table->unsignedInteger('agent_id')->default(0)->comment('商户所属代理id');
            $table->decimal('balance',11,2)->default(0)->comment('余额');
            $table->decimal('freezeBalance',11,2)->default(0)->comment('冻结金额');
            $table->decimal('handlingFeeBalance',11,2)->default(0)->comment('充值余额');
            $table->decimal('order_money',12,2)->default(0)->comment('订单总金额');
            $table->unsignedTinyInteger('valid')->default(1)->comment('有效表示：0无效 ，1有效');
            $table->timestamps();
        });

        DB::statement("ALTER TABLE `pay_statisticals` comment '商户定时统计表'");
        DB::statement("ALTER TABLE `pay_statisticals` ENGINE=InnoDB");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('statisticals');
    }
}
