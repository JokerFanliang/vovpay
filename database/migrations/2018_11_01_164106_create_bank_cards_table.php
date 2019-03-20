<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateBankCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_cards', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->comment('用户id');
            $table->unsignedInteger('bank_id')->comment('银行ID');
            $table->string('bankCardNo')->unique()->comment('银行卡号');
            $table->string('accountName')->comment('开户名');
            $table->string('branchName')->nullable()->comment('支行名称');
            $table->unsignedTinyInteger('status')->default(0)->comment('状态：0禁用，1启用，只能1张卡为1');
            $table->timestamps();
        });

        DB::statement("ALTER TABLE `pay_users` comment '银行卡列表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bank_cards');
    }
}
