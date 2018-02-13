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
        DB::table('warehouse')->delete();
        //WAREHOUSE
        DB::table('warehouse')->insert([
            ['wid'   => '1',
            'w_name'   => '新竹倉',],
            ['wid'   => '2',
            'w_name'   => '台中倉',],
            ['wid'   => '3',
            'w_name'   => '雲林倉',],
            ['wid'   => '4',
            'w_name'   => '高雄倉',],
        ]);
    }
}
