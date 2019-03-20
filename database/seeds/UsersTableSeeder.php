<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now()->toDateTimeString();
        DB::table('users')->insert([
            'username'  => 'agent',
            'password'  => bcrypt ('000000'),
            'email'     => 'vovpay@agent.com',
            'phone'     => '18888888889',
            'group_type'=> 2,
            'payPassword' => bcrypt ('123456'),
            'merchant'  => str_random(10),
            'apiKey'    => bcrypt(str_random(10)),
            'created_at'=> $now,
            'updated_at'=> $now
        ]);
        DB::table('users')->insert([
            'username'  => 'vovpay',
            'password'  => bcrypt ('000000'),
            'email'     => 'vovpay@vovpay.com',
            'phone'     => '18888888888',
            'group_type'=> 1,
            'parentId'  => 1,
            'payPassword' => bcrypt ('123456'),
            'merchant'  => str_random(10),
            'apiKey'    => bcrypt(str_random(10)),
            'created_at'=> $now,
            'updated_at'=> $now
        ]);
    }
}
