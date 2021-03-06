<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(AdminsTableSeeder::class);
        $this->call(RulesTableSeeder::class);
        $this->call(RolesTableSeeder::class);
        $this->call(RoleRuleTableSeeder::class);
        $this->call(AdminRoleTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(SystemsTableSeeder::class);
        $this->call(ChannelPaymentsTableSeeder::class);
        $this->call(ChannelsTableSeeder::class);
        $this->call(BanksTableSeeder::class);
        $this->call(StatisticalsTableSeeder::class);

    }
}
