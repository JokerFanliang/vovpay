<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username',30)->unique()->comment('用户名');
            $table->string('password')->comment('密码');
            $table->string('email',30)->unique()->comment('邮箱');
            $table->char('phone',11)->nullable()->comment('电话号码');
            $table->unsignedTinyInteger('group_type')->default(1)->comment('用户组表示,1用户，2代理商,3场外');
            $table->unsignedInteger('parentId')->default(0)->comment('归属代理ID');
            $table->string('agentName',30)->nullable()->comment('代理名称');
            $table->string('payPassword')->comment('支付密码');
            $table->string('merchant',10)->unique()->comment('商户号');
            $table->string('apiKey')->unique()->comment('密钥');
            $table->string('google_key')->default(0)->comment('谷歌密钥');
            $table->decimal('quota',11,2)->default(0)->comment('场外商户额度');
            $table->unsignedTinyInteger('status')->default(0)->comment('状态：0禁用，1启用，2删除');
            $table->rememberToken();
            $table->timestamps();
        });

        DB::statement("ALTER TABLE `pay_users` comment '商户表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
