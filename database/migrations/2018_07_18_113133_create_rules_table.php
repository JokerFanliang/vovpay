<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateRulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rules', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 10)->comment('菜单名称');
            $table->string('uri',60)->nullable()->comment('跳转链接');
            $table->string('rule',60)->nullable()->comment('Action');
            $table->string('icon', 20)->nullable()->comment('图标');
            $table->unsignedInteger('pid')->default(0)->comment('上级id,默认为0');
            $table->unsignedSmallInteger('sort')->default(0)->comment('排序');
            $table->tinyInteger('level')->comment('层级');
            $table->boolean('is_check')->default(1)->comment('是否需要验证');
            $table->boolean('is_show')->default(1)->comment('是否显示');
            $table->timestamps();
        });

        DB::statement("ALTER TABLE `pay_rules` comment '菜单表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rules');
    }
}
