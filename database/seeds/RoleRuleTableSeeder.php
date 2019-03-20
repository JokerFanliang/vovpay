<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RoleRuleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('role_rule')->insert([
            ['role_id' => 1, 'rule_id' => 2],
            ['role_id' => 1, 'rule_id' => 3],
            ['role_id' => 1, 'rule_id' => 4],
            ['role_id' => 1, 'rule_id' => 5],
            ['role_id' => 1, 'rule_id' => 6],
            ['role_id' => 1, 'rule_id' => 7],
            ['role_id' => 1, 'rule_id' => 8],
            ['role_id' => 1, 'rule_id' => 9],
            ['role_id' => 1, 'rule_id' => 10],
            ['role_id' => 1, 'rule_id' => 11],
            ['role_id' => 1, 'rule_id' => 12],
            ['role_id' => 1, 'rule_id' => 13],
            ['role_id' => 1, 'rule_id' => 14],
        ]);
    }
}
