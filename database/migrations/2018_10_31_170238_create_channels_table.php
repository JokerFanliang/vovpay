<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateChannelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('channels', function (Blueprint $table) {
            $table->increments('id');
            $table->string('merchant')->nullable()->comment('上游商户号');
            $table->string('signkey')->nullable()->comment('上游密钥');
            $table->string('channelName',30)->comment('通道名称');
            $table->string('channelCode',20)->comment('通道编码');
            $table->string('refererDomain',20)->nullable()->comment('防封域名');
            $table->unsignedTinyInteger('status')->default(0)->comment('状态:0禁用，1启用，2删除');
            $table->unsignedInteger('channelQuota')->default(0)->comment('通道限额：0不限额');
            $table->decimal('tradeAmount',12,2)->default(0)->comment('交易成功金额');
            $table->string('extend',1000)->default('{}')->comment('扩展字段，存储json');
            $table->timestamps();
        });

        DB::statement("ALTER TABLE `pay_channels` comment '通道表'");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('channels');
    }
}
