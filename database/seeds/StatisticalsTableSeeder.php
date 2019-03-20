<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StatisticalsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now()->toDateTimeString();
        DB::table('statisticals')->insert([
            [
            'user_id'  => 1,
            'agent_id' => 0,
            'created_at'=> $now,
            'updated_at'=> $now
            ],[
                'user_id'  => 2,
                'agent_id'  => 1,
                'created_at'=> $now,
                'updated_at'=> $now
            ]
        ]);
    }
}
