<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RulesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now()->toDateTimeString();
        DB::table('rules')->insert([
            ['title' => '后台首页', 'uri' => 'admin', 'rule' => 'index', 'icon' => '&#xe68e;', 'pid' => 0, 'level' => 1, 'sort' => 99, 'is_check' => 0, 'is_show' => 1,'created_at'=> $now, 'updated_at'=> $now],

            ['title' => '权限控制', 'uri' => null, 'rule' => null, 'icon' => '&#xe672;', 'pid' => 0, 'level' => 1, 'sort' => 0, 'is_check' => 1, 'is_show' => 1,'created_at'=> $now, 'updated_at'=> $now],

            ['title' => '角色管理', 'uri' => 'admin/roles/index', 'rule'  => 'index','icon' => '&#xe612;', 'pid' => 2, 'level' => 2, 'sort' => 0, 'is_check' => 1, 'is_show' => 1,'created_at'=> $now, 'updated_at'=> $now],
            ['title' => '角色添加', 'uri' => 'admin/roles/store', 'rule'  => 'store', 'icon' => null, 'pid' => 3, 'level' => 3, 'sort' => 0, 'is_check' => 1, 'is_show' => 0,'created_at'=> $now, 'updated_at'=> $now],
            ['title' => '角色编辑', 'uri' => 'admin/roles/{id}/edit', 'rule' => 'update', 'icon' => null, 'pid' => 3, 'level' => 3, 'sort' => 0, 'is_check' => 1, 'is_show' => 0,'created_at'=> $now, 'updated_at'=> $now],
            ['title' => '角色删除', 'uri' => 'admin/roles/destroy', 'rule' => 'destroy', 'icon' => null, 'pid' => 3, 'level' => 3, 'sort' => 0, 'is_check' => 1, 'is_show' => 0,'created_at'=> $now, 'updated_at'=> $now],

            ['title' => '菜单管理', 'uri' => null, 'rule' => null, 'icon' => '&#xe66b;', 'pid' => 2, 'level' => 2, 'sort' => 0, 'is_check' => 1, 'is_show' => 1,'created_at'=> $now, 'updated_at'=> $now],
            ['title' => '菜单添加', 'uri' => 'admin/rules/store', 'rule' => 'store', 'icon' => null, 'pid' => 7, 'level' => 3, 'sort' => 0, 'is_check' => 1, 'is_show' => 0,'created_at'=> $now, 'updated_at'=> $now],
            ['title' => '菜单编辑', 'uri' => 'admin/rules/{id}/edit', 'rule' => 'update', 'icon' => null, 'pid' => 7, 'level' => 3, 'sort' => 0, 'is_check' => 1, 'is_show' => 0,'created_at'=> $now, 'updated_at'=> $now],
            ['title' => '菜单删除', 'uri' => 'admin/rules/destroy', 'rule' => 'destroy', 'icon' => null, 'pid' => 7, 'level' => 3, 'sort' => 0, 'is_check' => 1, 'is_show' => 0,'created_at'=> $now, 'updated_at'=> $now],

            ['title' => '管理员管理', 'uri' => 'admin/admins/index', 'rule' => 'index', 'icon' => '&#xe613;', 'pid' => 2, 'level' => 2, 'sort' => 0, 'is_check' => 1, 'is_show' => 1,'created_at'=> $now, 'updated_at'=> $now],
            ['title' => '管理员添加', 'uri' => 'admin/admins/store', 'rule' => 'store', 'icon' => null, 'pid' => 11, 'level' => 3, 'sort' => 0, 'is_check' => 1, 'is_show' => 0,'created_at'=> $now, 'updated_at'=> $now],
            ['title' => '管理员编辑', 'uri' => 'admin/admins/{id}/edit', 'rule' => 'update', 'icon' => null, 'pid' => 11, 'level' => 3, 'sort' => 0, 'is_check' => 1, 'is_show' => 0,'created_at'=> $now, 'updated_at'=> $now],
            ['title' => '管理员删除', 'uri' => 'admin/admins/destroy', 'rule' => 'destroy', 'icon' => null, 'pid' => 11, 'level' => 3, 'sort' => 0, 'is_check' => 1, 'is_show' => 0,'created_at'=> $now, 'updated_at'=> $now],
        ]);
    }
}
