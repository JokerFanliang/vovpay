<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateBanksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code')->comment('银行编码');
            $table->string('bankName',40)->comment('银行名称');
            $table->string('ico')->nullbale()->comment('银行logo');
            $table->unsignedTinyInteger('status')->default(0)->comment('状态：0禁用，1启用');
            $table->timestamps();
        });
        DB::statement("ALTER TABLE `pay_banks` comment '银行列表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('banks');
    }
}
