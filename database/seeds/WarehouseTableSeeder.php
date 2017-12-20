<?php

use Illuminate\Database\Seeder;

class WarehouseTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
     //WAREHOUSE
     DB::table('warehouse')->insert([
         ['w_name'   => '新竹倉'],
         ['w_name'   => '台中倉'],
         ['w_name'   => '雲林倉'],
         ['w_name'   => '高雄倉'],
     ]);
    }
}
