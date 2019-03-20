<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now()->toDateTimeString();
        DB::table('admins')->insert([
            'username'  => 'admin',
            'password'  => bcrypt ('admin888'),
            'email'     => 'admin@admin.com',
            'phone'     => '18888888888',
            'verify'    => '',
            'status'    => 1,
            'created_at'=> $now,
            'updated_at'=> $now
        ]);
    }
}
