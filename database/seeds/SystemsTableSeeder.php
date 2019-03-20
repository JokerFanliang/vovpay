<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SystemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now()->toDateTimeString();
        DB::table('systems')->insert([
                ['name' => 'login_permission_type', 'value' => '0', 'remark' => '登陆验证是否强制双重验证(密码+google):1是,0否', 'created_at' => $now, 'updated_at' => $now],
                ['name' => 'withdraw_permission_type', 'value' => 'PASSWORD', 'remark' => '提现验证方式: SMS短信验证,PASSWORD密码验证,GOOGLE谷歌验证', 'created_at' => $now, 'updated_at' => $now],
                ['name' => 'withdraw_downline', 'value' => '100', 'remark' => '提款最少金额(单位元)', 'created_at' => $now, 'updated_at' => $now],
                ['name' => 'withdraw_fee_type', 'value' => 'FIX', 'remark' => '提款收费类型:RATE百分比,FIX固定金额', 'created_at' => $now, 'updated_at' => $now],
                ['name' => 'withdraw_rate', 'value' => '1', 'remark' => '提款收费配置值:收费类型为RATE时单位为%,收费类型为FIX时单位为元', 'created_at' => $now, 'updated_at' => $now],
            ]
        );
    }
}
