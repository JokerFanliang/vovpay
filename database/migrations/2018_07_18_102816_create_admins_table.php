<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username',20)->comment('用户名');
            $table->string('password')->comment('密码');
            $table->string('email',20)->unique()->comment('邮箱');
            $table->char('phone',11)->unique()->comment('手机号');
            $table->string('verify',6)->nullable()->comment('验证码');
            $table->string('google_key')->default(0)->comment('谷歌密钥');
            $table->boolean('status')->default(1)->comment('账号状态');
            $table->rememberToken();
            $table->timestamps();
        });

        DB::statement("ALTER TABLE `pay_admins` comment '管理员表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admins');
    }
}
