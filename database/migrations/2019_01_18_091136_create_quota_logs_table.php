<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateQuotaLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quota_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->comment('商户id');
            $table->decimal('quota',11,2)->default(0)->comment('额度记录');
            $table->tinyInteger('quota_type')->default(0)->comment('额度类型，0增加，1减少');
            $table->tinyInteger('action_type')->default(0)->comment('操作类型，0人工，1订单');
            $table->timestamps();
        });
        DB::statement("ALTER TABLE `pay_quota_logs` comment '场外商户额度记录表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('quota_logs');
    }
}
