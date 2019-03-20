<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateAccountCloudSolidsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_cloud_solids', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('account_cloud_id')->comment('云端固码配置id');
            $table->string('account')->comment('账号');
            $table->decimal('amount',11,2)->default(0)->comment('金额');
            $table->string('accountType')->comment('账户类型：支付宝、微信等等');
            $table->string('content')->comment('固码内容');
            $table->timestamps();
        });
        DB::statement("ALTER TABLE `pay_account_cloud_solids` comment '云端固码表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('account_cloud_solids');
    }
}
