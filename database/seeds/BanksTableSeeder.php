<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class BanksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $now = Carbon::now()->toDateTimeString();
        DB::table('banks')->insert([
            ['code' => 'ICBC', 'bankName' => '中国工商银行', 'ico' => 'icbc.png', 'status' => '1', 'created_at'=> $now, 'updated_at'=> $now],
            ['code' => 'CCB', 'bankName' => '中国建设银行', 'ico' => 'ccb.png', 'status' => '1', 'created_at'=> $now, 'updated_at'=> $now],
            ['code' => 'ABC', 'bankName' => '中国农业银行', 'ico' => 'abc.png', 'status' => '1', 'created_at'=> $now, 'updated_at'=> $now],
            ['code' => 'BOC', 'bankName' => '中国银行', 'ico' => 'boc.png', 'status' => '1', 'created_at'=> $now, 'updated_at'=> $now],
            ['code' => 'CITIC', 'bankName' => '中信银行', 'ico' => 'citic.png', 'status' => '1', 'created_at'=> $now, 'updated_at'=> $now],
            ['code' => 'CMB', 'bankName' => '招商银行', 'ico' => 'cmb.png', 'status' => '1', 'created_at'=> $now, 'updated_at'=> $now],
            ['code' => 'HXBANK', 'bankName' => '华夏银行', 'ico' => 'hxb.png', 'status' => '1', 'created_at'=> $now, 'updated_at'=> $now],
            ['code' => 'COMM', 'bankName' => '交通银行', 'ico' => 'comm.png', 'status' => '1', 'created_at'=> $now, 'updated_at'=> $now],
            ['code' => 'PSBC', 'bankName' => '中国邮政储蓄银行', 'ico' => 'psbc.png', 'status' => '1', 'created_at'=> $now, 'updated_at'=> $now],
            ['code' => 'SPDB', 'bankName' => '浦发银行', 'ico' => 'spdb.png', 'status' => '1', 'created_at'=> $now, 'updated_at'=> $now],
            ['code' => 'CEB', 'bankName' => '中国光大银行', 'ico' => 'ceb.png', 'status' => '1', 'created_at'=> $now, 'updated_at'=> $now],
            ['code' => 'CMBC', 'bankName' => '中国民生银行', 'ico' => 'cmbc.png', 'status' => '1', 'created_at'=> $now, 'updated_at'=> $now],
            ['code' => 'GDB', 'bankName' => '广发银行', 'ico' => 'gdb.png', 'status' => '1', 'created_at'=> $now, 'updated_at'=> $now],
            ['code' => 'BJBANK', 'bankName' => '北京银行', 'ico' => 'bjb.png', 'status' => '1', 'created_at'=> $now, 'updated_at'=> $now],
            ['code' => 'HKBEA', 'bankName' => '东亚银行', 'ico' => 'bea.png', 'status' => '1', 'created_at'=> $now, 'updated_at'=> $now],
            ['code' => 'NJCB', 'bankName' => '南京银行', 'ico' => 'njcb.png', 'status' => '1', 'created_at'=> $now, 'updated_at'=> $now],
            ['code' => 'NBBANK', 'bankName' => '宁波银行', 'ico' => 'nbcb.png', 'status' => '1', 'created_at'=> $now, 'updated_at'=> $now],
            ['code' => 'SPABANK', 'bankName' => '平安银行', 'ico' => 'spab.png', 'status' => '1', 'created_at'=> $now, 'updated_at'=> $now],
            ['code' => 'SHBANK', 'bankName' => '上海银行', 'ico' => 'shb.png', 'status' => '1', 'created_at'=> $now, 'updated_at'=> $now],
            ['code' => 'CIB', 'bankName' => '兴业银行', 'ico' => 'cib.png', 'status' => '1', 'created_at'=> $now, 'updated_at'=> $now],
            ['code' => 'ANTBANK', 'bankName' => '网商银行', 'ico' => 'antbank.png', 'status' => '1', 'created_at'=> $now, 'updated_at'=> $now],
            ['code' => 'CZBANK', 'bankName' => '浙商银行', 'ico' => 'czb.png', 'status' => '1', 'created_at'=> $now, 'updated_at'=> $now],
            ['code' => 'ALIPAY', 'bankName' => '支付宝', 'ico' => 'alipay.png', 'status' => '1', 'created_at'=> $now, 'updated_at'=> $now],
            ['code' => 'WECHAT', 'bankName' => '微信', 'ico' => 'wechat.png', 'status' => '1', 'created_at'=> $now, 'updated_at'=> $now],
        ]);
    }
}
