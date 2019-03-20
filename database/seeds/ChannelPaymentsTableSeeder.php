<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ChannelPaymentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now()->toDateTimeString();
        DB::table('channel_payments')->insert([
            [
            'channel_id'   => '1',
            'paymentName'  => '支付宝',
            'paymentCode'  => 'alipay',
            'runRate'      => '0.03',
            'costRate'     => 0,
            'created_at'=> $now,
            'updated_at'=> $now
            ],
            [
                'channel_id'   => '1',
                'paymentName'  => '微信',
                'paymentCode'  => 'wechat',
                'runRate'      => '0.03',
                'costRate'     => 0,
                'created_at'=> $now,
                'updated_at'=> $now
            ],
            [
                'channel_id'   => '1',
                'paymentName'  => '转银行卡',
                'paymentCode'  => 'alipay_bank',
                'runRate'      => '0.03',
                'costRate'     => 0,
                'created_at'=> $now,
                'updated_at'=> $now
            ],
            [
                'channel_id'   => '1',
                'paymentName'  => '转银行卡',
                'paymentCode'  => 'alipay_bank2',
                'runRate'      => '0.03',
                'costRate'     => 0,
                'created_at'=> $now,
                'updated_at'=> $now
            ],
            [
                'channel_id'   => '1',
                'paymentName'  => '云闪付',
                'paymentCode'  => 'cloudpay',
                'runRate'      => '0.03',
                'costRate'     => 0,
                'created_at'=> $now,
                'updated_at'=> $now
            ],
            [
                'channel_id'   => '1',
                'paymentName'  => '银行固码',
                'paymentCode'  => 'bank_solidcode',
                'runRate'      => '0.03',
                'costRate'     => 0,
                'created_at'=> $now,
                'updated_at'=> $now
            ],
            [
                'channel_id'   => '1',
                'paymentName'  => '支付宝固码',
                'paymentCode'  => 'alipay_solidcode',
                'runRate'      => '0.03',
                'costRate'     => 0,
                'created_at'=> $now,
                'updated_at'=> $now
            ],
            [
                'channel_id'   => '1',
                'paymentName'  => '微信固码',
                'paymentCode'  => 'wechat_solidcode',
                'runRate'      => '0.03',
                'costRate'     => 0,
                'created_at'=> $now,
                'updated_at'=> $now
            ],
            [
                'channel_id'   => '1',
                'paymentName'  => '云闪付固码',
                'paymentCode'  => 'cloudpay_solidcode',
                'runRate'      => '0.03',
                'costRate'     => 0,
                'created_at'=> $now,
                'updated_at'=> $now
            ],
            [
                'channel_id'   => '1',
                'paymentName'  => '支付宝红包',
                'paymentCode'  => 'alipay_packets',
                'runRate'      => '0.03',
                'costRate'     => 0,
                'created_at'=> $now,
                'updated_at'=> $now
            ],
        ]);
    }
}
