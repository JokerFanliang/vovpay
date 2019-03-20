<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ChannelsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now()->toDateTimeString();
        DB::table('channels')->insert([
            'channelCode'  => 'exempt',
            'channelName'  => '即时支付',
            'created_at'=> $now,
            'updated_at'=> $now
        ]);
    }
}
