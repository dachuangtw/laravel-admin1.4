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
        //權限
        // DB::table('admin_permissions')->insert([
        //     ['name' => '倉庫_新刪修',
        //      'slug' => 'warehouse_editor'],
        //     ['name' => '商品_新刪修',
        //      'slug' => 'product_editor'],
        // ]);
      //MENU
      $this->call(AdminMenuTableSeeder::class);
      //WAREHOUSE
      $this->call(WarehouseTableSeeder::class);
    }
}
