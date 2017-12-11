<?php

use Illuminate\Database\Seeder;

class AdminPermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('admin_permissions')->insert([
            ['name' => '倉庫_新修刪',
             'slug' => 'warehouse_editor'],
            ['name' => '商品_新修刪',
             'slug' => 'product_editor'],
        ]);
    }
}
